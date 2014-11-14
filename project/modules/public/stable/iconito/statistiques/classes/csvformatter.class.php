<?php

abstract class CsvFormatter
{
    /**
     * @var ConsolidatedStatisticFilter
     */
    protected $filter;

    /**
     * @var array $options
     */
    protected $options = array();

    /**
     * Constructeur
     *
     * @param ConsolidatedStatisticFilter $filter
     */
    public function __construct(ConsolidatedStatisticFilter $filter, array $options = array())
    {
        $this->filter = $filter;

        $this->options = $options;
    }

    /**
     * Retourne la valeur d'une option (ou la valeur par défaut si non trouvée)
     *
     * @param string $optionName
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($optionName, $default = null)
    {
        if (!array_key_exists($optionName, $this->options)) {
            return $default;
        }

        return $this->options[$optionName];
    }

    /**
     * Retourne les lignes générées
     *
     * @return array
     */
    abstract public function getLines();
}