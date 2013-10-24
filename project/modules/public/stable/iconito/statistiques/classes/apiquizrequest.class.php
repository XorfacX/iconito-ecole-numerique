<?php

_classInclude('statistiques|apibaserequest');

class ApiQuizRequest extends ApiBaseRequest
{
    public function getQuiz()
    {
        return $this->getTotalAndAverageOnPeriod(static::CLASS_QUIZ, 'create');
    }

    /**
     * Récupère le nombre de questions envoyées, et le ratio par quiz
     *
     * @return integer
     */
    public function getNombreQuestionsEtRatio()
    {
        $quizCount = $this->getObjectTypeNumber(static::CLASS_QUIZ);
        $questionCount = $this->getObjectTypeNumber(static::CLASS_QUESTION);

        return array(
            'questions' => $questionCount,
            'ratio' => $quizCount > 0 ? round($questionCount/$quizCount, 2) : 0
        );
    }
}