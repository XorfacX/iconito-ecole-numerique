<?php


class ZoneConnectionsByProfile extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $ppo = new CopixPPO ();

        $ppo->requestClass = $this->getParam('requestClass');
        $ppo->profile = $this->getParam('profile');

        $toReturn = $this->_usePPO ($ppo, '_connectionsByProfile.tpl');
        return true;
    }



}
