<?php

_classInclude('statistiques|StatistiquesFormFilter');

/**
 * Class StatistiquesFactory
 *
 * Construction des classe de statistiques afin d'injecter certains paramètres au constructeur
 */
class StatistiquesFactory
{
    public static function get($classeName, StatistiquesFormFilter $formFilter)
    {
        $filenameParts = array(
            __DIR__,
            DIRECTORY_SEPARATOR,
            'statistiques',
            DIRECTORY_SEPARATOR,
            strtolower($classeName),
            '.class.php'
        );

        $filename = implode('', $filenameParts);

        if (!file_exists($filename) || !is_readable($filename)) {
            throw new LogicException(sprintf(
                'Le fichier "%s" n\'a pas été trouvé ou n\'est pas accessible en lecture.',
                $filename
            ));
        }

        require_once($filename);

        return new $classeName(
            CopixConfig::get('statistiques|apiQueryUrl'),
            // TODO Récupérer la valeur de la conf
            'ICONITO',
            $formFilter
        );
    }
}