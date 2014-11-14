<?php

_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|consolidatedstatisticfiltertorequesttransformer');

abstract class ApiBaseRequest
{
    const PERIOD_UNIT    = 'unit';
    const PERIOD_HOURLY  = 'hourly';
    const PERIOD_DAILY   = 'daily';
    const PERIOD_WEEKLY  = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_YEARLY  = 'yearly';

    const CLASS_ALL_USERS     = 'ALL_USERS';
    const CLASS_ACCOUNT       = 'ActivityStreamPerson';
    const CLASS_AGENDA        = 'DAORecordagenda';
    const CLASS_EVENT         = 'DAORecordevent';
    const CLASS_MINIMAIL      = 'DAORecordminimail_to';
    const CLASS_CLASSEUR      = 'DAORecordClasseur';
    const CLASS_DOSSIER       = 'DAORecordClasseurDossier';
    const CLASS_FICHIER       = 'DAORecordClasseurFichier';
    const CLASS_BLOG          = 'DAORecordBlog';
    const CLASS_BLOG_CATEGORY = 'DAORecordBlogarticlecategory';
    const CLASS_BLOG_ARTICLE  = 'DAORecordblogarticle';
    const CLASS_BLOG_PAGE     = 'DAORecordblogpage';
    const CLASS_COMMENTAIRE   = 'DAORecordblogarticlecomment';
    const CLASS_TRAVAIL       = 'DAORecordcahierdetextestravail';
    const CLASS_CAHIERTEXTE   = 'DAORecordcahierdetextes';
    const CLASS_MEMO          = 'DAORecordcahierdetextesmemo';
    const CLASS_QUIZ          = 'DAORecordQuiz_quiz';
    const CLASS_QUESTION      = 'DAORecordQuiz_questions';
    const CLASS_GROUPETRAVAIL = 'DAORecordGroupe';
    const CLASS_DISCUSSION    = 'DAORecordListe_Listes';
    const CLASS_MESSAGE       = 'DAORecordliste_messages';
    const CLASS_MINIMAIL_SENT = 'DAORecordminimail_to';

    /**
     * @var ConsolidatedStatisticFilter
     */
    private $filter;

    function __construct(ConsolidatedStatisticFilter $filter)
    {
        $this->filter = $filter;
    }


    /**
     * @param \ConsolidatedStatisticFilter $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return \ConsolidatedStatisticFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Sends request and returns result
     *
     * @param ConsolidatedStatisticFilter $filter
     */
    public function getResult(ConsolidatedStatisticFilter $filter)
    {
        $transformer = new ConsolidatedStatisticFilterToRequestTransformer;

        // On clone le filter
        $filterClone = clone $filter;

        // On corrige les date de début et de fin de recherche en fonction de la period de stat voulue
        switch ($filterClone->getPeriod()){
            case 'weekly':
                $filterClone->getPublishedFrom()->modify(
                    ($filterClone->getPublishedFrom()->format('N') == 1 ? 'this' : 'last').' Monday midnight'
                );
                $filterClone->getPublishedTo()->modify('next Monday midnight -1 second');
                break;
            case 'monthly':
                $filterClone->getPublishedFrom()->modify('first day of this month midnight');
                $filterClone->getPublishedTo()->modify('first day of next month midnight -1 second');
                break;
            case 'yearly':
                $filterClone->getPublishedFrom()->modify('first day of January midnight');
                $filterClone->getPublishedTo()->modify('first day of January next year midnight -1 second');
                break;
        }

        $requestFilter = $transformer->transform($filterClone);
        $requestUrl = CopixConfig::get('statistiques|apiQueryUrl').'?'.$requestFilter;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec ($curl);
        curl_close($curl);
        $result = json_decode($result);

        if (is_object($result)) {
          throw new Exception($result->message, $result->code);
        }

        return $result;
    }

    /**
     * Returns a base filter to be customized
     *
     * @return ConsolidatedStatisticFilter
     */
    public function createBaseFilter()
    {
        return new ConsolidatedStatisticFilter(
            $this->getFilter()->getpublishedFrom(),
            $this->getFilter()->getpublishedTo(),
            $this->getFilter()->getTargetObjectType(),
            $this->getFilter()->getTargetId()
        );
    }

    /**
     * Applies unit count filter to objectType defined as parameter and return count result
     *
     * @param string $objectType
     * @param array $objectAttributes
     * @param string $verb
     *
     * @return mixed
     */
    protected function getObjectTypeNumber($objectType, array $objectAttributes = array(), $verb = null)
    {
        $result = $this->getObjectTypeResult($objectType, $objectAttributes, $verb);

        return count($result) ? $result[0]->counter : 0;
    }

    /**
     * Applies unit count filter to objectType defined as parameter and return result
     *
     * @param string $objectType
     * @param array $objectAttributes
     * @param string $verb
     *
     * @return mixed
     */
    protected function getObjectTypeResult($objectType, array $objectAttributes = array(), $verb = null)
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType($objectType);
        $filter->setObjectAttributes($objectAttributes);
        $filter->setPeriod(static::PERIOD_UNIT);
        $filter->setLastOnly(true);

        if (null !== $verb){
            $filter->setVerb($verb);
        }

        return $this->getResult($filter);
    }

    protected function sumResults($results)
    {
        $sum = 0;
        foreach ($results as $result)
        {
            $sum += $result->counter;
        }

        return $sum;
    }

    protected function getTotalAndAverageOnPeriod($objectType, $verb, $objectAttributes = array())
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType($objectType);
        $filter->setObjectAttributes($objectAttributes);
        $filter->setVerb($verb);
        $filter->setPeriod(static::PERIOD_DAILY);

        $total = $this->sumResults($this->getResult($filter));
        $days = $this->getFilter()->getpublishedFrom()->diff($this->getFilter()->getpublishedTo(), true)->days;

        return array(
            'total' => $total,
            'average' => $days > 0 ? round($total/$days, 2) : 0
        );
    }
}