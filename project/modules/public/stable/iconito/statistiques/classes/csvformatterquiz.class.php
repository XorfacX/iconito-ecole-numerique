<?php

class CsvFormatterQuiz extends CsvFormatter
{
    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    public function getLines()
    {
        _classInclude('statistiques|ApiQuizRequest');

        $api = new ApiQuizRequest($this->filter);

        $quiz      = $api->getQuiz();
        $questions = $api->getNombreQuestionsEtRatio();

        $data = array(
            'quiz créé(s)'         => $quiz['total'],
            'quiz par jour'        => $quiz['average'],
            'question(s) créée(s)' => $questions['questions'],
            'question(s) par quiz' => $questions['ratio']
        );

        return array(
            array_keys($data),
            array_values($data)
        );
    }
}