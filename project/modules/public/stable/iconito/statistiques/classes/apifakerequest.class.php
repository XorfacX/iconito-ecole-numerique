<?php

_classInclude('statistiques|apibaserequest');

class ApiFakeRequest
{
    /**
     * @var integer
     */
    private $fakeCount;

    public function getFakeResult()
    {
        $this->fakeCount = rand(1, 100);
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