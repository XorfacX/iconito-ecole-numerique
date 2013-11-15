<?php

_classInclude('statistiques|apibaserequest');
_classInclude('statistiques|consolidatedstatisticfilter');

class ApiUserRequest extends ApiBaseRequest
{
    /**
     * Récupère le nombre de comptes créés à une date donnée
     *
     * @return integer
     */
    public function getNombreComptes()
    {
        $filter = $this->createBaseFilter();

        $filter
            ->setObjectObjectType(static::CLASS_ALL_USERS)
            ->setPeriod(static::PERIOD_UNIT)
            ->setLastOnly(true)
            ->setVerb('count')
            ->setTargetObjectType(null)
            ->setTargetId(null)
            ->setTargetDisplayName(null)
            ->setTargetUrl(null)
            ->setTargetAttributes(null);

        $results = $this->getResult($filter);

        return count($results) ? $results[0]->counter : 0;
    }

    public function getNombreComptesParProfil()
    {
        $profils = array(
            'USER_ADM' => 'Équipe administrative',
            'USER_DIR' => 'Directeur',
            'USER_ELE' => 'Élève',
            'USER_ENS' => 'Enseignant',
            'USER_EXT' => 'Intervenant extérieur',
            'USER_RES' => 'Responsable',
            'USER_VIL' => 'Agent de ville'
        );

        $nombreComptes = array();

        foreach ($profils as $key => $profil){
            $nombreComptes[$profil] = 0;

            $filter = $this->createBaseFilter();
            $filter
                ->setVerb('count')
                ->setPeriod('unit')
                ->setObjectObjectType($key)
                ->setTargetObjectType(null)
                ->setTargetId(null)
                ->setTargetUrl(null)
                ->setTargetDisplayName(null)
                ->setTargetAttributes(null);

            $results = $this->getResult($filter);

            if (count($results)){
                $nombreComptes[$profil] = $results[0]->counter;
            }
        }

        return $nombreComptes;
    }

    /**
     * Retourne le nombre de connexion sur la période filtrée
     *
     * @return int
     */
    public function getNombreConnexions()
    {
        return $this->sumResults($this->getConnexionsParPeriode(static::PERIOD_DAILY));
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques mensuelles).
     *
     * @return integer
     */
    public function getConnexionsAnnuelles()
    {
        $beginYear = $this->getFilter()->getPublishedFrom()->format('Y');
        $endYear = $this->getFilter()->getPublishedTo()->format('Y');

        // Initialisation du tableau
        $connexionsAnnuelles = array();
        while ($beginYear <= $endYear) {
            $connexionsAnnuelles[$beginYear++] = 0;
        }


        foreach ($this->getConnexionsParPeriode(static::PERIOD_YEARLY) as $period) {
            $year = substr($period->published, 0, 4);

            $connexionsAnnuelles[$year] += $period->counter;
        }

        return $connexionsAnnuelles;
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques mensuelles).
     *
     * @return integer
     */
    public function getConnexionsMensuelles()
    {
        $months = array(
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre'
        );

        $beginDate = clone $this->getFilter()->getPublishedFrom();
        $endDate = clone $this->getFilter()->getPublishedTo();

        $connexionsMensuelles = array();
        $connexionsMoyennes = array();
        $nombreDeMois = array();

        $mustShowAverage = false;

        foreach ($months as $month) {
            $connexionsMoyennes[$month] = 0;
            $nombreDeMois[$month] = 0;
        }

        // Initialisation du tableau
        $lastYear = null;
        while ($beginDate < $endDate) {
            $connexionsMensuelles[$months[$beginDate->format('m')] . ' ' . $beginDate->format('Y')] = 0;

            $nombreDeMois[$months[$beginDate->format('m')]]++;

            $mustShowAverage = $mustShowAverage || ((null !== $lastYear) && ($lastYear != $beginDate->format('Y')));

            $lastYear = $beginDate->format('Y');

            $beginDate->modify('+1 month');
        }

        foreach ($this->getConnexionsParPeriode(static::PERIOD_MONTHLY) as $period) {
            $year = substr($period->published, 0, 4);
            $month = substr($period->published, 5, 2);
            $monthName = $months[$month];

            $connexionsMensuelles[$monthName . ' ' . $year] += $period->counter;

            $connexionsMoyennes[$monthName] += $period->counter;
        }

        // On termine par le calcul des moyennes
        foreach ($connexionsMoyennes as $index => &$count) {
            if ($nombreDeMois[$index] > 0) {
                $count = round($count / $nombreDeMois[$index], 2);
            } else {
                $count = 0;
            }
        }

        return array(
            'statistiques' => $connexionsMensuelles,
            'moyennes' => $connexionsMoyennes,
            'afficherMoyennes' => $mustShowAverage
        );
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques journalières).
     *
     * @return integer
     */
    public function getConnexionsHebdomadaires()
    {
        $beginDate = clone $this->getFilter()->getPublishedFrom();
        $endDate = clone $this->getFilter()->getPublishedTo();

        // On remet les date du filtre au premier jour de leur semaine respective
        $beginDate->modify('this week last monday');
        $endDate->modify('this week last monday');

        $connexionsHebdomadaires = array();

        // Initialisation du tableau
        while ($beginDate <= $endDate) {
            $key = sprintf(
                '%s - S%s',
                $this->getYearOfWeekNumberFromDate($beginDate),
                $this->getWeekNumberFromDate($beginDate)
            );
            $connexionsHebdomadaires[$key] = 0;

            $beginDate->modify('+1 week');
        }

        foreach ($this->getConnexionsParPeriode(static::PERIOD_WEEKLY) as $period) {

            $periodDatetime = new DateTime($period->published);
            $periodDatetime->modify('this week last monday');

            $key = sprintf(
                '%s - S%s',
                $this->getYearOfWeekNumberFromDate($periodDatetime),
                $this->getWeekNumberFromDate($periodDatetime)
            );

            $connexionsHebdomadaires[$key] += $period->counter;
        }

        return array(
            'statistiques' => $connexionsHebdomadaires,
        );
    }

    /**
     * Retourne le numéro de semaine de la date
     *
     * @param DateTime $date La date
     *
     * @return int
     */
    private function getWeekNumberFromDate(DateTime $date)
    {
        return (int)$date->format('W');
    }

    /**
     * Retourne l'année du numéro de semaine de la date
     *
     * @param DateTime $date La date
     *
     * @return int
     */
    private function getYearOfWeekNumberFromDate(DateTime $date)
    {
        $weekNumber = $this->getWeekNumberFromDate($date);

        $month = (int)$date->format('m');
        $year = (int)$date->format('Y');

        // Si l'on est sur la semaine 1 mais sur le dernier mois de l'année, on est sur une semaine à cheval,
        // On ajoute 1 à l'année
        if (1 == $weekNumber && 12 == $month) {
            return $year + 1;
        }

        return $year;
    }

    /**
     * Récupère le nombre de connexions enregistrées dans la période (sur les statistiques journalières).
     *
     * @return integer
     */
    public function getConnexionsJournalieres()
    {
        $days = array(
            '1' => 'Lundi',
            '2' => 'Mardi',
            '3' => 'Mercredi',
            '4' => 'Jeudi',
            '5' => 'Vendredi',
            '6' => 'Samedi',
            '7' => 'Dimanche'
        );

        $beginDate = clone $this->getFilter()->getPublishedFrom();
        $endDate = clone $this->getFilter()->getPublishedTo();

        $connexionsJournalieres = array();
        $connexionsMoyennes = array();
        $nombreDeJours = array();

        $mustShowAverage = false;

        foreach ($days as $day) {
            $connexionsMoyennes[$day] = 0;
            $nombreDeJours[$day] = 0;
        }

        // Initialisation du tableau
        $lastWeek = null;
        while ($beginDate < $endDate) {
            $day = $days[$beginDate->format('N')];
            $connexionsJournalieres[$day.' '.$beginDate->format('d/m/Y')] = 0;

            $nombreDeJours[$day]++;

            $weekKey = sprintf(
                '%s - S%s',
                $this->getYearOfWeekNumberFromDate($beginDate),
                $this->getWeekNumberFromDate($beginDate)
            );

            $mustShowAverage = $mustShowAverage || ((null !== $lastWeek) && ($lastWeek != $weekKey));

            $lastWeek = $weekKey;

            $beginDate->modify('+1 day');
        }

        foreach ($this->getConnexionsParPeriode(static::PERIOD_DAILY) as $period) {
            $date = new DateTime($period->published);

            $day = $days[$date->format('N')];

            $connexionsJournalieres[$day.' '.$date->format('d/m/Y')] += $period->counter;

            $connexionsMoyennes[$day] += $period->counter;
        }

        // On termine par le calcul des moyennes
        foreach ($connexionsMoyennes as $index => &$count) {
            if ($nombreDeJours[$index] > 0) {
                $count = round($count / $nombreDeJours[$index], 2);
            } else {
                $count = 0;
            }
        }

        return array(
            'statistiques' => $connexionsJournalieres,
            'moyennes' => $connexionsMoyennes,
            'afficherMoyennes' => $mustShowAverage
        );
    }

    public function getConnexionsHoraires()
    {
        $hours = array(
            '00' => '00h',
            '01' => '01h',
            '02' => '02h',
            '03' => '03h',
            '04' => '04h',
            '05' => '05h',
            '06' => '06h',
            '07' => '07h',
            '08' => '08h',
            '09' => '09h',
            '10' => '10h',
            '11' => '11h',
            '12' => '12h',
            '13' => '13h',
            '14' => '14h',
            '15' => '15h',
            '16' => '16h',
            '17' => '17h',
            '18' => '18h',
            '19' => '19h',
            '20' => '20h',
            '21' => '21h',
            '22' => '22h',
            '23' => '23h'
        );

        $beginDate = clone $this->getFilter()->getPublishedFrom();
        $endDate = clone $this->getFilter()->getPublishedTo();

        $connexionsMoyennes = array();
        $nombreHeures = array();

        foreach ($hours as $hour) {
            $connexionsMoyennes[$hour] = 0;
            $nombreHeures[$hour] = 0;
        }

        // Initialisation du tableau
        while ($beginDate < $endDate) {
            $hour = $hours[$beginDate->format('H')];

            $nombreHeures[$hour]++;

            $beginDate->modify('+1 hour');
        }

        foreach ($this->getConnexionsParPeriode(static::PERIOD_HOURLY) as $period) {
            $date = new DateTime($period->published);

            $connexionsMoyennes[$hours[$date->format('H')]] += $period->counter;
        }

        // On termine par le calcul des moyennes
        foreach ($connexionsMoyennes as $index => &$count) {
            if ($nombreHeures[$index] > 0) {
                $count = round($count / $nombreHeures[$index], 2);
            } else {
                $count = 0;
            }
        }

        return array(
            'moyennes' => $connexionsMoyennes
        );
    }

    /**
     * Retourne les connexions sur la période, pour le type de statistique passé en paramètres
     *
     * @param $periode
     *
     * @return array
     */
    private function getConnexionsParPeriode($periode)
    {
        $filter = $this->createBaseFilter();
        $filter
            ->setActorObjectType(static::CLASS_ACCOUNT)
            ->setVerb('login')
            ->setPeriod($periode)
            ->setTargetObjectType(null)
            ->setTargetId(null)
            ->setTargetUrl(null)
            ->setTargetDisplayName(null)
            ->setTargetAttributes(null);

        return (array)$this->getResult($filter);
    }
}