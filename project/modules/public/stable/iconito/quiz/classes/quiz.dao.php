<?php

/**
* @package    Iconito
* @subpackage Quiz
*/

class DAORecordQuiz
{
}

class DAOQuiz
{
    /**
     * Retourne les quiz d'un enseignant pour une année et une école donnée
     *
     * @param $classroom L'identifiant de la classe
     * @param $owner     L'identifiant de l'enseignant
     * @param $year      L'identifiant de l'année
     *
     * @return mixed
     */
    public function findQuizForClassroomOwnerAndYear($classroom, $owner, $year)
    {
        $ct = CopixDB::getConnection ($this->_connectionName);

        $query = <<<SQL
            SELECT * FROM module_quiz_quiz mqq
            INNER JOIN kernel_mod_enabled kme ON mqq.gr_id = kme.module_id
            INNER JOIN kernel_bu_ecole_classe kbec ON kme.node_id = kbec.id
            INNER JOIN kernel_bu_ecole_classe original_class ON original_class.ecole = kbec.ecole
            INNER JOIN kernel_mod_enabled kme2 ON original_class.id = kme2.node_id
            WHERE mqq.id_owner = :owner_id
            AND kbec.annee_scol = :year_id
            AND kme2.module_id = :classroom_id
            AND kme.node_type = 'BU_CLASSE'
            AND kme.module_type = 'MOD_QUIZ'
            AND kme2.node_type = 'BU_CLASSE'
            AND kme2.module_type = 'MOD_QUIZ'
SQL;

        return $ct->doQuery($query, compact($classroom, $owner, $year));
    }
}