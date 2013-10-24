<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiGroupeDeTravailRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreMessages()
    {
        return $this->getObjectTypeNumber(static::CLASS_MESSAGE);
    }

    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreDiscussionsEtRatio()
    {
        $discussions = $this->getObjectTypeNumber(static::CLASS_DISCUSSION);
        $forums = $this->getObjectTypeNumber(static::CLASS_GROUPETRAVAIL, array('forum'));
        $forums = $forums ? $forums : 1;

        return array(
            'total' => $discussions,
            'average' => $discussions/$forums
        );
    }

    /**
     * Récupère le nombre d'agendas créés à une date donnée
     *
     * @return integer
     */
    public function getNombreMessagesEtRatio()
    {
        $messages = $this->getObjectTypeNumber(static::CLASS_MESSAGE);
        $discussions = $this->getObjectTypeNumber(static::CLASS_DISCUSSION);
        $discussions = $discussions ? $discussions : 1;

        return array(
            'total' => $messages,
            'average' => $messages/$discussions
        );
    }
}