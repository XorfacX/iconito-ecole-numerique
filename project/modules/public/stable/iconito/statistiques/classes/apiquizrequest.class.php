<?php

_classInclude('statistiques|apibaserequest');

class ApiQuizRequest extends ApiBaseRequest
{
    public function getQuiz()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_QUIZ, 'create');
    }

    /**
     * Récupère le nombre de quiz envoyés, et le ratio par comptes ouverts
     *
     * @return integer
     */
    public function getNombreQuestionsEtRatio()
    {
        $quizCount = $this->getObjectTypeNumber(static::CLASS_QUIZ);
        $questionCount = $this->getObjectTypeNumber(static::CLASS_QUESTION);
        $quizCount = $quizCount ? $quizCount : 1;

        $ratio = $questionCount/$quizCount;

        return array(
            'questions' => $questionCount,
            'ratio' => $ratio
        );
    }
}