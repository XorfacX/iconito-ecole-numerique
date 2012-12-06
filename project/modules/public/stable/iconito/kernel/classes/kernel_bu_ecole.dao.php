<?php

class DAORecordKernel_bu_ecole {

    protected $_city = null;

    public function __toString() {
        return $this->nom;
    }

    public function getCity() {
        if (is_null($this->_city)) {

            $cityDAO = _ioDAO('kernel|kernel_bu_ville');

            $this->_city = $cityDAO->get($this->id_ville);
        }

        return $this->_city;
    }

    /**
     * Determine si l'ecole a une adresse renseignee ou non
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2011/01/31
     * @return boolean True si au moins un champ de l'adresse est renseigne, false sinon
     */
    public function hasAdresse() {
        $oHas = false;
        if ($this->num_rue || $this->num_seq || $this->adresse1 || $this->adresse2 || $this->code_postal || $this->commune) {
            $oHas = true;
        }
        return $oHas;
    }

    /**
     * Retourne les classes pour une ville donnée
     *
     * @param int $idVille Identifiant d'une ville
     *
     * @return CopixDAORecordIterator
     */
    public function getByCity($idVille) {
        $criteria = _daoSp();
        $criteria->addCondition('id_ville', '=', $idVille);
        $criteria->orderBy('nom');

        return $this->findBy($criteria);
    }

}

class DAOKernel_bu_ecole extends enicService {

    public function __construct() {
        parent::__construct();
        $this->db = & enic::get('model');
    }

    /**
     * Retourne les classes pour une ville donnée
     *
     * @param int $idVille Identifiant d'une ville
     *
     * @return CopixDAORecordIterator
     */
    public function getByCity($idVille) {
        $criteria = _daoSp();
        $criteria->addCondition('id_ville', '=', $idVille);
        $criteria->orderBy('nom');

        return $this->findBy($criteria);
    }

    /**
     * Retourne les écoles d'une ville accessibles pour un utilisateur
     *
     * @param int   $cityId  Identifiant de la ville
     * @param array $groups  Groupes
     *
     * @return CopixDAORecordIterator
     */
    public function findByCityIdAndUserGroups($cityId, $groups) {
        $groupsIds = array(
            'schoolsIds' => array(),
            'classroomsIds' => array()
        );

        foreach ($groups as $key => $group) {

            $id = substr($key, strrpos($key, '_') + 1);

            if (preg_match('/^administration_staff/', $key)) {

                $groupsIds['schoolsIds'][] = $id;
            } elseif (preg_match('/^principal/', $key)) {

                $groupsIds['schoolsIds'][] = $id;
            } elseif (preg_match('/^teacher_school/', $key)) {

                $groupsIds['schoolsIds'][] = $id;
            } elseif (preg_match('/^teacher/', $key)) {

                $groupsIds['classroomsIds'][] = $id;
            } elseif (preg_match('/^schools_group_animator/', $key)) {

                $groupsIds['schoolsIds'][] = $id;
            } elseif (preg_match('/^cities_group_animator/', $key)) {

                $groupsIds['schoolsIds'][] = $id;
            }
        }

        if (empty($groupsIds['schoolsIds']) && empty($groupsIds['classroomsIds'])) {

            return array();
        }

        $sql = $this->_selectQuery . ' '
                . 'LEFT JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole = kernel_bu_ecole.numero '
                . 'WHERE kernel_bu_ecole.id_ville=' . $cityId;

        $conditions = array();
        if (!empty($groupsIds['schoolsIds'])) {

            $conditions[] = 'kernel_bu_ecole.numero IN (' . implode(',', $groupsIds['schoolsIds']) . ')';
        }
        if (!empty($groupsIds['classroomsIds'])) {

            $conditions[] = 'kernel_bu_ecole_classe.id IN (' . implode(',', $groupsIds['classroomsIds']) . ')';
        }

        $sql .= ' AND (' . implode(' OR ', $conditions) . ')';
        $sql .= ' GROUP BY kernel_bu_ecole.numero';
        $sql .= ' ORDER BY kernel_bu_ecole_classe.nom';

        return new CopixDAORecordIterator(_doQuery($sql), $this->getDAOId());
    }

    protected $errorsMessages;

    /**
     * Récupérer le(s) message(s) d'erreurs
     *
     * @author Philippe ROSER <proser@cap-tic.fr>
     * @since 2012/12/06
     * @return array
     */
    public function getErrorsMessages() {
        return $this->errorsMessages;
    }

    /**
     * Vérifier la conformité d'un élément de l'école
     *
     * @author Philippe ROSER <proser@cap-tic.fr>
     * @since 2012/12/06
     * @return boolean
     */
    public function validate($schoolDatas) {

        $validationEntries = array('siret', 'uai');

        $noError = true;
        foreach ($validationEntries as $validationEntry) {
            if ($schoolDatas->$validationEntry == null)
                continue;
            if (!$this->{'validate' . mb_strtoupper($validationEntry)}(
                            $schoolDatas->$validationEntry, $schoolDatas->numero
                    )
            )
                $noError = false;
        }
        return $noError;
    }

    /**
     * Vérifier la conformité d'un numéro UAI
     *
     * @author Philippe ROSER <proser@cap-tic.fr>
     * @since 2012/12/06
     * @return boolean
     */
    protected function validateUAI($entry = null, $id = null) {

        $correspondance = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        if ($correspondance[(mb_substr($entry, 0, 7) % 23)] == mb_substr($entry, 7, 1)) {
            // Le numéro UAI est valide
            // Mais il faut vérifier l'unicité
            $sql = 'SELECT RNE FROM kernel_bu_ecole WHERE RNE=' . $this->db->quote($entry);
            if ($id != null)
                $sql .= ' AND numero != ' . $this->db->quote($id);
            $result = $this->db->query($sql)->toArray();

            if (!empty($result)) {
                $this->errorsMessages[] = 'Ce numéro UAI est déjà utilisé';
                return false;
            } else {
                return true;
            }
        }
        $this->errorsMessages[] = 'Votre numéro UAI n\'est pas conforme';
        return false;
    }

    /**
     * Vérifier la conformité d'un numéro SIRET
     *
     * @author Philippe ROSER <proser@cap-tic.fr>
     * @since 2012/12/06
     * @return boolean
     */
    protected function validateSIRET($entry = null, $id = null) {

        if ($this->luhn_validate($entry)) {
            // Le numéro SIRET est valide
            // Mais il faut vérifier l'unicité
            $sql = 'SELECT siret FROM kernel_bu_ecole WHERE siret=' . $this->db->quote($entry);
            if ($id != null)
                $sql .= ' AND numero != ' . $this->db->quote($id);
            $result = $this->db->query($sql)->toArray();
            
            if (!empty($result)) {
                $this->errorsMessages[] = 'Ce numéro SIRET est déjà utilisé';
                return false;
            } else {
                return true;
            }
        } else {
            $this->errorsMessages[] = 'Votre numéro SIRET n\'est pas conforme';
            return false;
        }
    }

    protected function luhn_validate($s) {
        if (0 == $s) {
            return(false);
        } // Don't allow all zeros
        $sum = 0;
        $i = strlen($s); // Find the last character
        $l = $i % 2; // Is length of the string even or odd
        while ($i-- > 0) { // Iterate all digits backwards
            $sum+=$s[$i]; // Add the current digit
            // If the digit is even, add it again. Adjust for digits 10+ by subtracting 9.
            ($l == ($i % 2)) ? (($s[$i] > 4) ? ($sum+=($s[$i] - 9)) : ($sum+=$s[$i])) : false;
        }
        return (0 == ($sum % 10));
    }

}