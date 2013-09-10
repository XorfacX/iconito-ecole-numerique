<?php

_classInclude('statistiques|consolidatedstatisticfilter');
_classInclude('statistiques|consolidatedstatisticfiltertorequesttransformer');

abstract class ApiBaseRequest
{
    const PERIOD_UNIT    = 'unit';
    const PERIOD_DAILY   = 'daily';
    const PERIOD_WEEKLY  = 'weekly';
    const PERIOD_MONTHLY = 'monthly';
    const PERIOD_YEARLY  = 'yearly';

    const CLASS_ACCOUNT       = 'Account';
    const CLASS_AGENDA        = 'Agenda';
    const CLASS_EVENT         = 'Evenement';
    const CLASS_MINIMAIL      = 'Minimail';
    const CLASS_CLASSEUR      = 'Classeur';
    const CLASS_DOSSIER       = 'Dossier';
    const CLASS_BLOG          = 'Blog';
    const CLASS_VISITE        = 'Visite';
    const CLASS_RUBRIQUE      = 'Rubrique';
    const CLASS_PAGE          = 'Page';
    const CLASS_ARTICLE       = 'Article';
    const CLASS_COMMENTAIRE   = 'Commentaire';
    const CLASS_TRAVAIL       = 'Travail';
    const CLASS_CAHIERTEXTE   = 'CahierDeTexte';
    const CLASS_MEMO          = 'Memo';
    const CLASS_QUIZ          = 'Quiz';
    const CLASS_QUESTION      = 'Question';
    const CLASS_GROUPETRAVAIL = 'GroupeDeTravail';
    const CLASS_DISCUSSION    = 'Discussion';
    const CLASS_MESSAGE       = 'Message';

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
        $filter->setContext('A');
        $requestFilter = $transformer->transform($filter);
//        $requestUrl = CopixConfig::get('statistiques|apiQueryUrl').'?'.$requestFilter;
        $requestUrl = "http://asapi.local/app_dev.php/api/query.json".'?'.$requestFilter;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec ($curl);
        curl_close($curl);

        return json_decode($result);
    }

    /**
     * Returns a base filter to be customized
     *
     * @return ConsolidatedStatisticFilter
     */
    public function createBaseFilter()
    {
        return new ConsolidatedStatisticFilter(
            $this->getFilter()->getPublishedBeginDate(),
            $this->getFilter()->getPublishedEndDate(),
            $this->getFilter()->getContext()
        );
    }

    /**
     * Applies unit count filter to objectType defined as parameter
     * @param string $objectType
     * @param array  $objectAttributes
     * @return mixed
     */
    protected function getObjectTypeNumber($objectType, $objectAttributes = array())
    {
        $filter = $this->createBaseFilter();
        $filter->setObjectObjectType($objectType);
        $filter->setObjectAttributes($objectAttributes);
        $filter->setPeriod(static::PERIOD_UNIT);
        $filter->setLastOnly(true);
        $result = $this->getResult($filter);

        return count($result) ? $result[0]->count : 0;
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
        $days = $this->getFilter()->getPublishedBeginDate()->diff($this->getFilter()->getPublishedEndDate(), true)->days;
        $days = $days ? $days : 1;

        return array(
            'total' => $total,
            'average' => $total/$days
        );
    }
}