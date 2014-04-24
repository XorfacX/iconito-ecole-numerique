<?php

_classInclude('activitystream|ecolenumeriqueactivitystreamresource');
use ActivityStream\Client\Model\ResourceInterface;

_classInclude('eventDispatcher|EventDispatcherFactory');
_classInclude('activityStream|ActivityStreamManagerFactory');
_classInclude('activityStream|StreamObjectEvent');
_classInclude('activityStream|ActivityEvent');
_classInclude('activityStream|StatisticEvent');
_classInclude('activityStream|ActivityStreamPerson');
_classInclude('kernel|Kernel');

/**
 * Classe de service de l'activyStream
 */
class ActivityStreamService
{
    /**
     * @var ActivityStreamService L'instance courante
     */
    protected static $instance;

    const
        EVENT_ACTIVITY = 'activity_stream.push_activity',
        EVENT_STATISTIC = 'activity_stream.push_statistic';

    /**
     * @var Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->eventDispatcher = EventDispatcherFactory::getInstance();
    }

    /**
     * Récupération de l'instance courante (singleton) avec création au besoin
     *
     * @return ActivityStreamService
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Log une activité (lance l'évènement)
     *
     * @param string            $verb        Le verbe
     * @param ResourceInterface $actor       L'acteur
     * @param ResourceInterface $object      L'objet
     * @param ResourceInterface $target      La cible
     * @param array             $context     Le périmètre de la cible
     */
    public function logActivity($verb, ResourceInterface $actor = null, ResourceInterface $object = null,
                                ResourceInterface $target = null, array $context = null)
    {
        $this->eventDispatcher->dispatch(self::EVENT_ACTIVITY, new ActivityEvent(
            $verb,
            $actor,
            $object,
            $target,
            $context
        ));
    }

    /**
     * Log une statistique (lance l'évènement)
     *
     * @param int               $counter     Le compteur
     * @param string            $period      La période de calcul
     * @param ResourceInterface $actor       L'acteur
     * @param null              $verb        Le verbe
     * @param ResourceInterface $object      L'objet
     * @param ResourceInterface $target      La cible
     * @param array             $context     Le périmètre de la cible
     */
    public function logStatistic($counter, $period, ResourceInterface $actor = null, $verb = null,
                                 ResourceInterface $object = null, ResourceInterface $target = null,
                                 array $context = null)
    {
        $this->eventDispatcher->dispatch(self::EVENT_STATISTIC, new StatisticEvent(
            $counter,
            $period,
            $actor,
            $verb,
            $object,
            $target,
            $context
        ));
    }

    /**
     * Retourne une Resource à partir des userInfo
     *
     * @param array $userInfo
     *
     * @return Resource
     */
    public function getPersonFromUserInfo(array $userInfo)
    {
        return new ActivityStreamPerson($userInfo['type'], $userInfo['id'], $userInfo['prenom'], $userInfo['nom']);
    }

    /**
     * Récupère les contextes à partir du module
     *
     * @param string  $type Le type de module
     * @param integer $id   L'identifiant de l'objet
     *
     * @return array<Resource>
     */
    public function getContexts($type, $id)
    {
        $contexts = array();

        $node = Kernel::getNode($type, $id);

        $parent = Kernel::getContextParent($type, $id);

        // On s'arrête de remonter dès que le parent est null ou ROOT
        if (null !== $parent && $parent['type'] !== 'ROOT'){
            $contexts = array_filter(
                array_merge(
                    array(
                        $parent
                    ),
                    $this->getContexts($parent['type'], $parent['id'])
                )
            );
        }

        // Si le noeud était de type Ville ou Ecole
        // alors on va récupérer et ajouter dans le context les groupes correspondants
        if ($node instanceof DAORecordKernel_bu_ville) {
          $groupementsVilles = $node->getGroupementsVilles();

          foreach ($groupementsVilles as $groupementVille) {
            $contexts[] = array(
              'type' => 'GROUPE_VILLE',
              'id'   => $groupementVille->id
            );
          }
        }

        if ($node instanceof DAORecordKernel_bu_ecole) {
          $groupementsEcoles = $node->getGroupementsEcoles();

          foreach ($groupementsEcoles as $groupementEcole) {
            $contexts[] = array(
                'type' => 'GROUPE_ECOLE',
                'id'   => $groupementEcole->id
            );
          }
        }

        return $contexts;
    }

    /**
     * Retourne les ressources du contexte
     *
     * @param string $type Le type
     * @param int $id L'identifiant
     *
     * @return array
     */
    public function getContextResources($type, $id)
    {
        $context = array();

        foreach ($this->getContexts($type, $id) as $element){
            $resource = $this->getResource($element['type'], $element['id']);
            if (null !== $resource) {
                $context[] = $resource;
            }
        }

        return $context;
    }

    /**
     * Retourne les ressources du contexte
     *
     * @param array $data Le tableau de data
     *
     * @return array
     */
    public function getContextResourcesFromArray(array $data)
    {
        $contexts = array();
        foreach ($data as $datum) {
            $contexts[$datum['type'].'|'.$datum['id']] = $datum;
            foreach ($this->getContexts($datum['type'], $datum['id']) as $context) {
              $contexts[$context['type'].'|'.$context['id']] = $context;
            }
        }

        $contextResources = array();
        foreach ($contexts as $element){
            $contextResources[] = $this->getResource($element['type'], $element['id']);
        }

        return $contextResources;
    }

    /**
     * Retourne la ressource ActivityStream correspondant à la ressource Ecole Numérique
     *
     * @param string $type Le type de la ressource Ecole Numérique
     * @param int $id L'identifiant de la ressource Ecole Numérique
     */
    public function getResource($type, $id)
    {
        $record = Kernel::getNode($type, $id);

        if (null === $record){

            return null;
        }

        if (!$record instanceof ResourceInterface){
            throw new Exception(sprintf(
                'L\'objet "%s" (id: %s) doit implémenter l\'interface "%s"',
                get_class($record),
                $id,
                ResourceInterface
            ));
        }

        return $record->toResource();
    }
}
