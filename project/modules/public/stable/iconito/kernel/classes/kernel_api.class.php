<?php
/**
 * Kernel_API - Classes
 *
 * API de création, modification et suppression des éléments de base d'Ecole Numérique (villes, écoles, classes, etc.)
 * @package Iconito
 * @subpackage  Kernel
 * @version   $Id: kernel_api.class.php$
 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

class Kernel_API extends enicService
{

    public function __construct()
    {
        parent::__construct();
        $this->db =& enic::get('model');
    }


    /**
     * @param integer $id_grville Id du groupe de ville de rattachement
     * @param object $infos Informations sur la ville
     * @return integer Id de la ville
     */
    public function creerVille( $id_grville, $infos )
    {
        $test_grville = $this->db->query( 'SELECT id_grv FROM kernel_bu_groupe_villes WHERE id_grv='.$this->db->quote($id_grville) )->toArray();
        if( count($test_grville) == 0 ) {
            throw new Kernel_API_creerVille_noGrville("Groupe de ville inexistant");
        }
        
        $test_canon = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE canon='.$this->db->quote($infos->nomCanonique) )->toArray();
        if( count($test_canon) > 0 ) {
            throw new Kernel_API_creerVille_dupNomCanon("Nom canonique déjà utilisé");
        }
        
        $this->db->create(
            'kernel_bu_ville',
            array(
                'id_grville' => $this->db->quote($id_grville),
                'nom'        => $this->db->quote($infos->nom),
                'canon'      => $this->db->quote($infos->nomCanonique),
            )
        );
        
        return ($this->db->lastId);
    }
    
    /**
     * @param integer $id_ville Id de la ville
     */
    public function supprimerVille( $id_ville )
    {
    
        $test_ville = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE id_vi='.$this->db->quote($id_ville) )->toArray();
        if( count($test_ville) == 0 ) {
            throw new Kernel_API_supprimerVille_noVille("Ville inexistante");
        }
    
        $this->db->delete( 'kernel_bu_ville', 'id_vi='.$this->db->quote($id_ville) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ville Id de la ville
     * @param object $infos Informations sur la ville
     */
    public function modifierVille( $id_ville, $infos )
    {
        $test_canon = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE canon='.$this->db->quote($infos->nomCanonique) )->toArray();
        if( count($test_canon) > 0 ) {
            throw new Kernel_API_modifierVille_dupNomCanon("Nom canonique déjà utilisé");
        }

        $test_ville = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE id_vi='.$this->db->quote($id_ville) )->toArray();
        if( count($test_ville) == 0 ) {
            throw new Kernel_API_modifierVille_noVille("Ville inexistante");
        }

        
        $this->db->query( 'UPDATE kernel_bu_ville SET nom='.$this->db->quote($infos->nom).', canon='.$this->db->quote($infos->nomCanonique).' WHERE id_vi='.$this->db->quote($id_ville) );
        
        return (true);
    }
    
    /**
     * @param string nom de la ville
     * @return array
     */
    public function existeVille($ville)
    {
        return $this->db->query('SELECT id_vi FROM kernel_bu_ville WHERE nom='.$this->db->quote($ville))->toInt();
    }
    
    /**
     * @param integer $id_ville Id de la ville de rattachement
     * @param object $infos Informations sur l'école
     * @return integer Id de l'école
     */
    public function creerEcole( $id_ville, $infos )
    {
        $test_ville = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE id_vi='.$this->db->quote($id_ville) )->toArray();
        if( count($test_ville) == 0 ) {
            throw new Kernel_API_creerEcole_noVille("Ville inexistante");
        }
        
        $this->db->create(
            'kernel_bu_ecole',
            array(
                'id_ville'    => $this->db->quote($id_ville),
                'nom'         => $this->db->quote($infos->nom),
                'RNE'         => $this->db->quote($infos->rne),
                'siret'       => $this->db->quote($infos->siret),
                'type'        => $this->db->quote($infos->type), // Primaire, Elémentaire, Maternelle
                'num_rue'     => $this->db->quote($infos->adresse->numRue),
                'num_seq'     => $this->db->quote($infos->adresse->numSeq), // bis, ter
                'adresse1'    => $this->db->quote($infos->adresse->adresse1),
                'adresse2'    => $this->db->quote($infos->adresse->adresse2),
                'code_postal' => $this->db->quote($infos->adresse->codePostal),
                'commune'     => $this->db->quote($infos->adresse->commune),
            )
        );
        
        return ($this->db->lastId);
    }

    /**
     * @param integer $id_ecole Id de l'école
     */
    public function supprimerEcole( $id_ecole )
    {
        $test_ecole = $this->db->query( 'SELECT numero FROM kernel_bu_ecole WHERE numero='.$this->db->quote($id_ecole) )->toArray();
        if( count($test_ecole) == 0 ) {
            throw new Kernel_API_supprimerEcole_noEcole("Ecole inexistante");
        }
        
        $this->db->delete( 'kernel_bu_ecole', 'numero='.$this->db->quote($id_ecole) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ecole Id de l'école
     * @param object $infos Informations sur l'école
     */
    public function modifierEcole( $id_ecole, $infos )
    {
        $test_ecole = $this->db->query( 'SELECT numero FROM kernel_bu_ecole WHERE numero='.$this->db->quote($id_ecole) )->toArray();
        if( count($test_ecole) == 0 ) {
            throw new Kernel_API_modifierEcole_noEcole("Ecole inexistante");
        }
        
        $this->db->query( 'UPDATE kernel_bu_ecole SET nom='.$this->db->quote($infos->nom                ).',
                                                      RNE='.$this->db->quote($infos->rne                ).',
                                                     type='.$this->db->quote($infos->type               ).',
                                                  num_rue='.$this->db->quote($infos->adresse->numRue    ).',
                                                  num_seq='.$this->db->quote($infos->adresse->numSeq    ).',
                                                 adresse1='.$this->db->quote($infos->adresse->adresse1  ).',
                                                 adresse2='.$this->db->quote($infos->adresse->adresse2  ).',
                                              code_postal='.$this->db->quote($infos->adresse->codePostal).',
                                                  commune='.$this->db->quote($infos->adresse->commune   ).'
            WHERE numero='.$this->db->quote($id_ecole) );
        
        return (true);
    }
    
    /**
     * @param string $nom
     * @param string $ville
     * @return array
     */
    public function existeEcole($nom, $ville)
    {
        return $this->db->query('SELECT numero FROM kernel_bu_ecole WHERE nom='.$this->db->quote($nom).' AND commune='.$this->db->quote($ville))->toInt();
    }
    
    /**
     * @param integer $id_ecole Id de l'école de rattachement
     * @param object $infos Informations sur la classe
     * @return integer Id de la classe
     */
    public function creerClasse( $id_ecole, $infos )
    {
        $test_ecole = $this->db->query( 'SELECT numero FROM kernel_bu_ecole WHERE numero='.$this->db->quote($id_ecole) )->toArray();

        if( count($test_ecole) == 0 ) {
            throw new Kernel_API_creerClasse_noEcole("Ecole inexistante");
        }
        
        $this->db->create(
            'kernel_bu_ecole_classe',
            array(
                'ecole'        => $this->db->quote($id_ecole),
                'nom'          => $this->db->quote($infos->nom),
                'annee_scol'   => $this->db->quote($infos->anneeScolaire),
                'is_validee'   => 1,
                'is_supprimee' => 0,
            )
        );

        $id_classe = $this->db->lastId;
        
        foreach( $infos->niveaux as $niveau )
        {
            $this->db->create(
                'kernel_bu_ecole_classe_niveau',
                array(
                    'classe' => $id_classe,
                    'niveau' => $this->db->quote($niveau->niveau),
                    'type'   => $this->db->quote($niveau->type),
                )
            );
        }
        return ($id_classe);
    }
    
    /**
     * @param integer $id_classe Id de la classe
     */
    public function supprimerClasse( $id_classe )
    {
        $test_classe = $this->db->query( 'SELECT id FROM kernel_bu_ecole_classe WHERE id='.$this->db->quote($id_classe) )->toArray();
        if( count($test_classe) == 0 ) {
            throw new Kernel_API_supprimerClasse_noClasse("Classe inexistante");
        }
        
        $this->db->delete( 'kernel_bu_ecole_classe', 'id='.$this->db->quote($id_classe) );
        
        return (true);
    }
    
    /**
     * @param integer $id_classe Id de la classe
     * @param object $infos Informations sur la classe
     */
    public function modifierClasse( $id_classe, $infos )
    {
        $test_classe = $this->db->query( 'SELECT id FROM kernel_bu_ecole_classe WHERE id='.$this->db->quote($id_classe) )->toArray();
        if( count($test_classe) == 0 ) {
            throw new Kernel_API_modifierClasse_noClasse("Classe inexistante");
        }
    
        $this->db->query( 'UPDATE kernel_bu_ecole_classe SET nom='.$this->db->quote($infos->nom).',
                                                      annee_scol='.$this->db->quote($infos->anneeScolaire).'
            WHERE id='.$this->db->quote($id_classe) );
        
        $this->db->delete( 'kernel_bu_ecole_classe_niveau', 'classe='.$this->db->quote($id_classe) );

        foreach( $infos->niveaux AS $niveau )
        {
            // $niveau->niveau
            // $niveau->type
            $this->db->create(
                'kernel_bu_ecole_classe_niveau',
                array(
                    'classe' => $id_classe,
                    'niveau' => $this->db->quote($niveau->niveau),
                    'type'   => $this->db->quote($niveau->type),
                )
            );
        }

        return (true);
    }
    
   /**
    * Return the ID of the class if exists
    * @param soapClassModel
    * @return integer
    */
    public function existeClasse($classe)
    {
        return $this->db->query('SELECT id FROM kernel_bu_ecole_class WHERE id ='.$classe->class_id);
    }
    
    // kernel_bu_personnel : numero     nom     nom_jf  prenom1     civilite    id_sexe  tel_dom    tel_gsm     tel_pro     mel     id_ville    pays
    // kernel_bu_personnel_entite : id_per  reference=$id_ecole     type_ref=ECOLE  role=2
    public function creerDirecteur( $id_ecole, $infos )
    {
        $test_ecole = $this->db->query( 'SELECT numero, id_ville FROM kernel_bu_ecole WHERE numero='.$this->db->quote($id_ecole) )->toArray();
        if( count($test_ecole) == 0 ) {
            throw new Kernel_API_creerDirecteur_noEcole("Ecole inexistante");
        }

        $this->db->create(
            'kernel_bu_personnel',
            array(
                'nom'      => $this->db->quote($infos->nom),
                'nom_jf'   => $this->db->quote($infos->nomJf),
                'prenom1'  => $this->db->quote($infos->prenom),
                'civilite' => $this->db->quote($infos->civilite),
                'id_sexe'  => $this->db->quote($infos->idSexe),
                'tel_dom'  => $this->db->quote($infos->telDom),
                'tel_gsm'  => $this->db->quote($infos->telGsm),
                'tel_pro'  => $this->db->quote($infos->telPro),
                'mel'      => $this->db->quote($infos->mail),
                'id_ville' => $this->db->quote($test_ecole[0]['id_ville']),
            )
        );
        
        $id_directeur = $this->db->lastId;
        
        $this->db->create(
            'kernel_bu_personnel_entite',
            array(
                'id_per'      => $this->db->quote($id_directeur),
                'reference'   => $this->db->quote($id_ecole),
                'type_ref'  => $this->db->quote('ECOLE'),
                'role' => $this->db->quote(2),
            )
        );
        
        
        return ($id_directeur);
    }
    
    public function supprimerDirecteur( $id_directeur )
    {
        $this->db->query( 'DELETE FROM kernel_bu_personnel_entite WHERE id_per='.$this->db->quote($id_directeur) )->toArray();
        $this->db->query( 'DELETE FROM kernel_bu_personnel        WHERE numero='.$this->db->quote($id_directeur) )->toArray();
        
        return (true);
    }
    
    public function modifierDirecteur( $id_directeur, $infos )
    {
        $test_directeur = $this->db->query( 'SELECT numero FROM kernel_bu_personnel WHERE numero='.$this->db->quote($id_directeur) )->toArray();
        if( count($test_directeur) == 0 ) {
            throw new Kernel_API_modifierDirecteur_noDirecteur("Directeur inexistant");
        }
        
        $this->db->query( 'UPDATE kernel_bu_personnel SET nom='.$this->db->quote($infos->nom      ).',
                                                       nom_jf='.$this->db->quote($infos->nomJf    ).',
                                                      prenom1='.$this->db->quote($infos->prenom   ).',
                                                     civilite='.$this->db->quote($infos->civilite ).',
                                                      id_sexe='.$this->db->quote($infos->idSexe   ).',
                                                      tel_dom='.$this->db->quote($infos->telDom   ).',
                                                      tel_gsm='.$this->db->quote($infos->telGsm   ).',
                                                      tel_pro='.$this->db->quote($infos->telPro   ).',
                                                          mel='.$this->db->quote($infos->mail     ).'
            WHERE numero='.$this->db->quote($id_directeur) );
        
        return (true);
    }
    
    /**
     * @param string surname
     * @param string name
     * @return integer id du directeur si il existe
     */
    public function existeDirecteur ($nom, $prenom)
    {
        $id = $this->db->query('SELECT numero FROM kernel_bu_personnel WHERE nom='.$this->db->quote($nom).' AND prenom1='.$this->db->quote($prenom))->toInt();
        $role = $this->db->query('SELECT role FROM kernel_bu_personnel_entite WHERE id_per='.$this->db->quote($id).' AND role=2');
        if(!empty($role))
                return $id;
        
    }
    
    public function creerLogin( $user_type, $user_id, $login, $password, $md5 = true )
    {
        $test_login = $this->db->query( 'SELECT login_dbuser FROM dbuser WHERE login_dbuser='.$this->db->quote($login) )->toArray();
        if( count($test_login) > 0 ) {
            throw new Kernel_API_creerLogin_dupLogin("Login déjà utilisé");
        }
        if( strlen($login) < 2 ) {
            throw new Kernel_API_creerLogin_shortLogin("Login trop court");
        }
        if( preg_match('/[^a-zA-Z0-9\.\-_]/',$login) ) {
            throw new Kernel_API_creerLogin_badLogin("Caractère interdit dans le login (Autorisé : a-z A-Z 0-9 . - _ )");
        }
        if( strlen($password) < 6 ) {
            throw new Kernel_API_creerLogin_shortPassword("Mot de passe trop court");
        }
        if($user_type=='USER_ENS' || $user_type=='USER_VIL') {
            $test_personnel = $this->db->query( 'SELECT numero FROM kernel_bu_personnel WHERE numero='.$this->db->quote($user_id) )->toArray();
            if( count($test_personnel) == 0 ) {
                throw new Kernel_API_creerLogin_noUser("Personnel inexistant");
            }
        } else {
            throw new Kernel_API_creerLogin_badUserType("Type de personne inconnu");
        }
        $test_oldlogin = $this->db->query( 'SELECT user_id FROM kernel_link_bu2user WHERE bu_type='.$this->db->quote($user_type).' AND bu_id='.$this->db->quote($user_id) )->toArray();
        if( count($test_oldlogin) > 0 ) {
            throw new Kernel_API_creerLogin_oldLogin("Login déjà créé");
        }

        $password = ($md5) ? md5($password) : $password;
        
        $this->db->create(
            'dbuser',
            array(
                'login_dbuser' => $this->db->quote($login),
                'password_dbuser' => $this->db->quote($password),
                'enabled_dbuser' => 1,
                'email_dbuser' => ''
            )
        );
        
        $id_user = $this->db->lastId;

        $this->db->create(
            'kernel_link_bu2user',
            array(
                'user_id' => $id_user,
                'bu_type' => $this->db->quote($user_type),
                'bu_id'   => $this->db->quote($user_id),
            )
        );

        return (true);
    }

    public function modifierPassword( $user_type, $user_id, $password )
    {
        $test_bu2user = $this->db->query( 'SELECT user_id FROM kernel_link_bu2user WHERE bu_type='.$this->db->quote($user_type).' AND bu_id='.$this->db->quote($user_id) )->toArray();
        if( count($test_bu2user) == 0 ) {
            throw new Kernel_API_modifierPassword_noLogin("Pas de login associé");
        }
        $test_login = $this->db->query( 'SELECT login_dbuser FROM dbuser WHERE id_dbuser='.$this->db->quote($test_bu2user[0]['user_id']) )->toArray();
        if( count($test_login) == 0 ) {
            throw new Kernel_API_modifierPassword_noLogin("Login inexistant");
        }

        $this->db->query( 'UPDATE dbuser SET password_dbuser='.$this->db->quote(md5($password)).'
            WHERE id_dbuser='.$this->db->quote($test_bu2user[0]['user_id']) );
        
        return (true);
    }
    
}

class Kernel_API_creerVille_noGrville extends Exception { }
class Kernel_API_creerVille_dupNomCanon extends Exception { }
class Kernel_API_supprimerVille_noVille extends Exception { }
class Kernel_API_modifierVille_dupNomCanon extends Exception { }
class Kernel_API_modifierVille_noVille extends Exception { }
class Kernel_API_creerEcole_noVille extends Exception { }
class Kernel_API_supprimerEcole_noEcole extends Exception { }
class Kernel_API_modifierEcole_noEcole extends Exception { }
class Kernel_API_creerClasse_noEcole extends Exception { }
class Kernel_API_supprimerClasse_noClasse extends Exception { }
class Kernel_API_modifierClasse_noClasse extends Exception { }
class Kernel_API_creerDirecteur_noEcole extends Exception { }
class Kernel_API_modifierDirecteur_noDirecteur extends Exception { }
class Kernel_API_creerLogin_dupLogin extends Exception { }
class Kernel_API_creerLogin_shortLogin extends Exception { }
class Kernel_API_creerLogin_shortPassword extends Exception { }
class Kernel_API_creerLogin_noUser extends Exception { }
class Kernel_API_creerLogin_badUserType extends Exception { }
class Kernel_API_creerLogin_oldLogin extends Exception { }
class Kernel_API_creerLogin_badLogin extends Exception { }