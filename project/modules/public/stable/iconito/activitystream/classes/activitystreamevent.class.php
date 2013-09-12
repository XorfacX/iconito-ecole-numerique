<?php

use ActivityStream\Client\Model\Resource;
use ActivityStream\Client\Model\ResourceInterface;

/**
 *
 */
class ActivityStreamEvent implements ResourceInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $id_agenda;

    /**
     * @var string
     */
    protected $title_event;

    /**
     * @var string
     */
    protected $desc_event;

    /**
     * @var string
     */
    protected $place_event;

    /**
     * @var string
     */
    protected $datedeb_event;

    /**
     * @var string
     */
    protected $heuredeb_event;

    /**
     * @var string
     */
    protected $datefin_event;

    /**
     * @var string
     */
    protected $heurefin_event;

    /**
     * @var int
     */
    protected $alldaylong_event;

    /**
     * @var int
     */
    protected $everyday_event;

    /**
     * @var int
     */
    protected $everyweek_event;

    /**
     * @var int
     */
    protected $everymonth_event;

    /**
     * @var int
     */
    protected $everyyear_event;

    /**
     * @var int
     */
    protected $endrepeatdate_event;

    /**
     * @var string
     */
    protected $type;

    function __construct(
        $alldaylong_event,
        $datedeb_event,
        $datefin_event,
        $desc_event,
        $endrepeatdate_event,
        $everyday_event,
        $everymonth_event,
        $everyweek_event,
        $everyyear_event,
        $heuredeb_event,
        $heurefin_event,
        $id,
        $id_agenda,
        $place_event,
        $title_event,
        $type
    ) {
        $this->alldaylong_event = $alldaylong_event;
        $this->datedeb_event = $datedeb_event;
        $this->datefin_event = $datefin_event;
        $this->desc_event = $desc_event;
        $this->endrepeatdate_event = $endrepeatdate_event;
        $this->everyday_event = $everyday_event;
        $this->everymonth_event = $everymonth_event;
        $this->everyweek_event = $everyweek_event;
        $this->everyyear_event = $everyyear_event;
        $this->heuredeb_event = $heuredeb_event;
        $this->heurefin_event = $heurefin_event;
        $this->id = $id;
        $this->id_agenda = $id_agenda;
        $this->place_event = $place_event;
        $this->title_event = $title_event;
        $this->type = $type;
    }

    /**
    * @param $id
    */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
    * @return mixed
    */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $alldaylong_event
     */
    public function setAlldaylongEvent($alldaylong_event)
    {
        $this->alldaylong_event = $alldaylong_event;
    }

    /**
     * @return int
     */
    public function getAlldaylongEvent()
    {
        return $this->alldaylong_event;
    }

    /**
     * @param string $datedeb_event
     */
    public function setDatedebEvent($datedeb_event)
    {
        $this->datedeb_event = $datedeb_event;
    }

    /**
     * @return string
     */
    public function getDatedebEvent()
    {
        return $this->datedeb_event;
    }

    /**
     * @param string $datefin_event
     */
    public function setDatefinEvent($datefin_event)
    {
        $this->datefin_event = $datefin_event;
    }

    /**
     * @return string
     */
    public function getDatefinEvent()
    {
        return $this->datefin_event;
    }

    /**
     * @param string $desc_event
     */
    public function setDescEvent($desc_event)
    {
        $this->desc_event = $desc_event;
    }

    /**
     * @return string
     */
    public function getDescEvent()
    {
        return $this->desc_event;
    }

    /**
     * @param int $endrepeatdate_event
     */
    public function setEndrepeatdateEvent($endrepeatdate_event)
    {
        $this->endrepeatdate_event = $endrepeatdate_event;
    }

    /**
     * @return int
     */
    public function getEndrepeatdateEvent()
    {
        return $this->endrepeatdate_event;
    }

    /**
     * @param int $everyday_event
     */
    public function setEverydayEvent($everyday_event)
    {
        $this->everyday_event = $everyday_event;
    }

    /**
     * @return int
     */
    public function getEverydayEvent()
    {
        return $this->everyday_event;
    }

    /**
     * @param int $everymonth_event
     */
    public function setEverymonthEvent($everymonth_event)
    {
        $this->everymonth_event = $everymonth_event;
    }

    /**
     * @return int
     */
    public function getEverymonthEvent()
    {
        return $this->everymonth_event;
    }

    /**
     * @param int $everyweek_event
     */
    public function setEveryweekEvent($everyweek_event)
    {
        $this->everyweek_event = $everyweek_event;
    }

    /**
     * @return int
     */
    public function getEveryweekEvent()
    {
        return $this->everyweek_event;
    }

    /**
     * @param int $everyyear_event
     */
    public function setEveryyearEvent($everyyear_event)
    {
        $this->everyyear_event = $everyyear_event;
    }

    /**
     * @return int
     */
    public function getEveryyearEvent()
    {
        return $this->everyyear_event;
    }

    /**
     * @param string $heuredeb_event
     */
    public function setHeuredebEvent($heuredeb_event)
    {
        $this->heuredeb_event = $heuredeb_event;
    }

    /**
     * @return string
     */
    public function getHeuredebEvent()
    {
        return $this->heuredeb_event;
    }

    /**
     * @param string $heurefin_event
     */
    public function setHeurefinEvent($heurefin_event)
    {
        $this->heurefin_event = $heurefin_event;
    }

    /**
     * @return string
     */
    public function getHeurefinEvent()
    {
        return $this->heurefin_event;
    }

    /**
     * @param string $id_agenda
     */
    public function setIdAgenda($id_agenda)
    {
        $this->id_agenda = $id_agenda;
    }

    /**
     * @return string
     */
    public function getIdAgenda()
    {
        return $this->id_agenda;
    }

    /**
     * @param string $place_event
     */
    public function setPlaceEvent($place_event)
    {
        $this->place_event = $place_event;
    }

    /**
     * @return string
     */
    public function getPlaceEvent()
    {
        return $this->place_event;
    }

    /**
     * @param string $title_event
     */
    public function setTitleEvent($title_event)
    {
        $this->title_event = $title_event;
    }

    /**
     * @return string
     */
    public function getTitleEvent()
    {
        return $this->title_event;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

}
