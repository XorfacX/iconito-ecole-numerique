<?php

/**
 * Class StatistiquesFilter
 *
 * Classe métier d'abstraction du formulaire de filtre pour la gestion des appels API
 */
abstract class StatistiquesFilter
{
    /** @var array Les données portées par le filtre */
    protected $data = array();

    /**
     * Constructeur
     */
    public function __construct(array $allowedKeys, array $filterData = array())
    {
        $this->setAllowedDataKeys($allowedKeys);

        foreach ($filterData as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Définition les clés autorisées pour les données
     *
     * @param array $keys
     */
    protected function setAllowedDataKeys(array $keys)
    {
        $keys = array_unique(array_merge(array_keys($this->data), $keys));

        foreach ($keys as $key){
            if (!array_key_exists($key, $this->data)){
                $this->data[$key] = null;
            }
        }

        return $this;
    }

    /**
     * Défini la valeur d'une clé (si cette dernière est définie)
     *
     * @param $dataKey La clé
     * @param $dataValue La valeur
     */
    public function set($dataKey, $dataValue)
    {
        if (array_key_exists($dataKey, $this->data)){
            $methodName = 'set'.ucfirst(static::camelize($dataKey));
            if (method_exists($this, $methodName)){
                $this->$methodName($dataValue);
            }
            else{
                $this->data[$dataKey] = $dataValue;
            }
        }

        return $this;
    }

    /**
     * Retourne la valeur d'une clé
     *
     * @param $dataKey La clé
     *
     * @return mixed
     */
    public function get($dataKey)
    {
        if (array_key_exists($dataKey, $this->data)){
            return $this->data[$dataKey];
        }

        return null;
    }

    /**
     * Retourne une version camelisée de la chaîne de caractères
     *
     * @param string $lower_case_and_underscored_word La chaîne underscoré
     *
     * @return string
     */
    protected static function camelize($lower_case_and_underscored_word)
    {
        $tmp = $lower_case_and_underscored_word;
        $replacements = array(
            '#/(.?)#e'       => "'::'.strtoupper('\\1')",
            '/(^|_|-)+(.)/e' => "strtoupper('\\2')"
        );

        return preg_replace(array_keys($replacements), array_values($replacements), $tmp);
    }
}