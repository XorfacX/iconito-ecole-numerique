<?php

/**
* @package    Iconito
* @subpackage Quiz
*/

class DAORecordQuiz_quiz
{
    /**
     * Retourne la classe d'un quiz
     *
     * @return mixed|null
     */
    public function getClasse()
    {
        $classeDao = _ioDAO('kernel|kernel_bu_ecole_classe');
        return $classeDao->getForQuiz($this->id);
    }
}

class DAOQuiz_quiz
{
    /**
     * Retourne les quiz d'un enseignant pour une année et une école donnée
     *
     * @param $classroom L'identifiant de la classe courante (utile pour récupérer l'école)
     * @param $owner     L'identifiant de l'enseignant
     * @param $year      L'identifiant de l'année
     *
     * @return mixed
     */
    public function findQuizForClassroomOwnerAndYear($classroom, $owner, $year)
    {
        // On récupère l'école d'après la classe passée en paramètre
        $ecoleDao = _ioDAO('kernel|kernel_bu_ecole');
        $ecole = $ecoleDao->findByClassroom($classroom);
        if (null === $ecole) {
            return array();
        }

        $query = <<<SQL
            SELECT mqq.*
            FROM module_quiz_quiz mqq
            INNER JOIN kernel_mod_enabled kme ON (kme.module_type = 'MOD_QUIZ' AND mqq.gr_id = kme.module_id)
            INNER JOIN kernel_bu_ecole_classe kbec ON (kme.node_type = 'BU_CLASSE' AND kme.node_id = kbec.id)
            WHERE mqq.id_owner = :owner
            AND kbec.ecole = :ecole
            AND kbec.annee_scol = :year
SQL;

        return new CopixDAORecordIterator (_doQuery($query, array(
            'owner' => $owner,
            'ecole' => $ecole->numero,
            'year' => $year
        )), $this->getDAOId ());
    }
}
