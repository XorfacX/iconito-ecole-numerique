<?php

_classInclude('statistiques|apibaserequest');

class ApiFakeRequest
{
    /**
     * @var integer
     */
    private $fakeCount;

    function __construct()
    {
        $this->fakeCount = rand(1, 100);
    }

    public function getFakeResult()
    {
        return $this->fakeCount;
    }

    /**
     * Récupère le nombre de quiz envoyés, et le ratio par comptes ouverts
     *
     * @return integer
     */
    public function getFakeCountAndAverage()
    {
        $fakeNumber = $this->fakeCount*rand(1,10);

        return array(
            'total' => $fakeNumber,
            'average' => $fakeNumber / $this->fakeCount
        );
    }
}
