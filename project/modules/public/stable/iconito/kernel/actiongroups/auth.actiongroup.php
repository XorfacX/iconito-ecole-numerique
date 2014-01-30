<?php

/*
  @file 	auth.actiongroup.php
  @desc		SSO Cap-Démat
  @version 	1.0.0
  @date 	2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
  @author 	Cristian CIOFU <cciofu@cap-tic.fr>

  Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
 */

_classInclude('auth|dbuserhandler');

class ActionGroupAuth extends enicActionGroup
{

    public function processSso()
    {
        try {
            CopixRequest::assert ('id', 'date', 'signature');

            $idExterne    = _request('id');
            $date         = _request('date');
            $signature    = _request('signature');

            $isSSOallowed = false;
            $ssoTimeout   = 30;
            $ssoSecret    = '';

            if ( CopixConfig::exists('|ssoActivated') ) {
                $isSSOallowed = (CopixConfig::get ('|ssoActivated')==1) ? true : false;
            }
            if ( CopixConfig::exists('|ssoSecret') ) {
                $ssoSecret = CopixConfig::get ('|ssoSecret');
            }
            if ( CopixConfig::exists('|ssoTimeout') ) {
                $ssoTimeout = CopixConfig::get ('|ssoTimeout');
            }

            if ($isSSOallowed) {
                // on va vérifier la signature 
                if ($this->checkSignature($idExterne, $date, $ssoSecret, $signature)) {
                    // la date est dans les dernières 30 minutes ?
                    $dateTimestampPhp = round($date/1000);
                    if (round(abs(time() - $dateTimestampPhp) / 60, 2) <= $ssoTimeout) {
                        
                        $tpl = new CopixTpl();

                        // si l'utilisateur a envoyé le formulaire (login) on va faire la verification
                        // (et, si c'est le cas on va aussi faire le lien)

                        if ( _request('username') !== null && _request('password') !== null ) {
                            $enUser = _request('username');
                            $enPass = _request('password');

                            // verification login
                            $criteres = _daoSp()->addCondition('login_dbuser', '=', $enUser)
                                                ->addCondition('password_dbuser', '=', md5($enPass));
                            $data     = _dao('dbuser')->findBy($criteres);

                            // si l'utilisateur existe
                            if (sizeof($data) == 1) {
                                $userAppariementExterne = _record ('kernel|sso');
                                $userAppariementExterne->id_externe = $idExterne;
                                $userAppariementExterne->id_ecolenumerique = $data[0]->id_dbuser;
                                $userAppariementExterne->createdAt = date("Y-m-d H:i:s");

                                _dao('kernel|sso')->insert($userAppariementExterne);
                            }
                            else {
                                $tpl->assign ('typedUsername', _request('username'));
                                $tpl->assign ('errorLogin', 1);
                            }
                        }
                        else if (_request('submit') !== null) {
                            $tpl->assign ('typedUsername', _request('username'));
                            $tpl->assign ('errorLogin', 1);
                        }

                        // vérification pour voir si on à déjà fait un lien
                        $criteres = _daoSp()->addCondition('id_externe', '=', $idExterne);
                        $data     = _dao('kernel|sso')->findBy($criteres);

                        $idEcoleNumerique = '';

                        if (sizeof($data) == 0) {
                            // on va afficher le formulaire de login
                            $tpl->assign ('TITLE_PAGE', "SSO");
                            $tpl->assign ('MAIN', $tpl->fetch('kernel|auth_sso.tpl'));
                            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
                        }
                        else {
                            // on va recuperer l'id d'utilisateur Ecole Numerique pour l'utilisateur Cap-Démat
                            $idEcoleNumerique = $data[0]->id_ecolenumerique;
                            
                            // et l'id module_sso
                            $idSSO = $data[0]->id;
                        }
                        
                        // AUTO LOG IN
                        if ($idEcoleNumerique !== '') {
                            // vérification pour voir si l'utilisateur existe encore
                            $criteresDbUser = _daoSp()->addCondition('id_dbuser', '=', $idEcoleNumerique);
                            $dataDbUser     = _dao('dbuser')->findBy($criteresDbUser);
                            if (sizeof($dataDbUser)>0) {
                                // ... et si il est active (enabled)
                                if ($dataDbUser[0]->enabled_dbuser == '1') {
                                    // LOGIN USER
                                    CopixAuth::getCurrentUser()->login(array('login'=>$dataDbUser[0]->login_dbuser, 'sso'=>true));
                                    
                                    // On va vérifier si la CHARTE a été accéptée ou pas (si c'est le cas)
                                    $this->user->forceReload();
                                    if(!$this->service('charte|CharteService')->checkUserValidation()){
                                        $this->flash->redirect = $urlReturn;
                                        return $this->go('charte|charte|valid');
                                    }

                                    // update lastAccess timestamp
                                    $userAppariementExterne = _dao('kernel|sso')->get($idSSO);
                                    $userAppariementExterne->lastAccess = date("Y-m-d H:i:s");
                                    _dao('kernel|sso')->update($userAppariementExterne);
                                    
                                    return $this->go('kernel||doSelectHome');

                                }
                            }
                        }

                    }
                }
            }

        }
        catch (Exception $e) {
            return new CopixActionReturn (CopixActionReturn::HTTPCODE, CopixHTTPHeader::get404 (), $e->getMessage());
        }
        
        // go to index
        return $this->go('||');
    }

    /**
     * Méthode pour vérifier si la signature envoyée dans le lien est correcte
     * 
     * @param string            $idExterne   - id utilisateur dans l'application externe
     * @param timestamp Java    $date        - la date de la demande
     * @param string            $secret      - la clé secrete 
     * @param string            $sigToCheck  - la signature envoyée dans le lien 
     * 
     * @return boolean
     */
    private function checkSignature($idExterne, $date, $secret, $sigToCheck) {
        $parametersForHash = $date . "+" . $idExterne . "+" . $secret;

        return ( sha1($parametersForHash) === $sigToCheck );
    }


}
