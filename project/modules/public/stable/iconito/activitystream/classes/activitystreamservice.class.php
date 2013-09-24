<?php

use ActivityStream\Client\Model\Resource;
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

    /**
     * @var array Mise en cache de l'arbre des contextes
     */
    protected $contextTree;

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
     * Retourne les resources parentes de la ressource passée en paramètre
     *
     * @param string $resourceIdentifier
     *
     * @return array
     */
    public function getParentResources($resourceIdentifier)
    {
        // On récupère les contextes à plat
        $tree = $this->getContextTree();

        // Le tableau des contextes parents
        $parentContexts = array();

        while (isset($tree[$resourceIdentifier]) && ($resourceIdentifier = $tree[$resourceIdentifier]['parent'])){
            if ($tree[$resourceIdentifier]['element'] instanceof ResourceInterface){
                $parentContexts[] = $tree[$resourceIdentifier]['element']->toResource();
            }
        }

        return $parentContexts;
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
        $modInfos = Kernel::getModParentInfo($type, $id);

        $parentIdentifier = $this->formatResourceIdentifier($modInfos['type'], $modInfos['id']);

        // Les contextes parents
        $contexts = $this->getParentResources($parentIdentifier);

        // On ajoute la ressource parente au début du tableau
        array_unshift($contexts, $this->getResource($modInfos['type'], $modInfos['id']));

        return $contexts;
    }

    /**
     * Retourne la ressource ActivityStream correspondant à la ressource Ecole Numérique
     *
     * @param string $type Le type de la ressource Ecole Numérique
     * @param int $id L'identifiant de la ressource Ecole Numérique
     */
    public function getResource($type, $id)
    {
        // On récupère les contextes à plat
        $tree = $this->getContextTree();

        $resourceIdentifier = $this->formatResourceIdentifier($type, $id);

        if (isset($tree[$resourceIdentifier]['element']) && $tree[$resourceIdentifier]['element'] instanceof ResourceInterface)
        {
            return $tree[$resourceIdentifier]['element']->toResource();
        }

        return null;
    }

    /**
     * Retourne l'identifieur de la ressource en fonction de son type et de son identifiant
     *
     * @param string $type Le type
     * @param int $id L'identifiant
     *
     * @return string
     */
    protected function formatResourceIdentifier($type, $id)
    {
        return $type.'_'.$id;
    }

    /**
     * Retourne l'arbre des contexte à plat
     *
     * @return array
     */
    protected function getContextTree()
    {
        if (null === $this->contextTree)
        {
            $this->contextTree = Kernel::getContextTree(true);
        }

        return $this->contextTree;
    }
}
