<?php

_classInclude('statistiques|consolidatedstatisticfilter');

/**
 * Class ConsolidatedStatisticTransformerToRequest
 * Converts a ConsolidatedStatistic to request url string
 *
 * @author Jérémy Hubert <jeremy.hubert@infogroom.fr>
 */
class ConsolidatedStatisticFilterToRequestTransformer
{
    /**
     * Transforms an array into a ConsolidatedStatisticFilter
     *
     * @param ConsolidatedStatisticFilter $consolidatedStatisticFilter
     *
     * @return string
     */
    public function transform(ConsolidatedStatisticFilter $consolidatedStatisticFilter)
    {
        $toImplode = array();
        $methods = get_class_methods($consolidatedStatisticFilter);
        foreach ($methods as $method) {
            $key = lcfirst(preg_replace('/^get/', '', $method, 1, $count));
            if ($count) {
                $value = $consolidatedStatisticFilter->$method();
                $key = static::getUnderscoredString($key);
                if (is_array($value) && count($value)) {
                    $toImplode[] = $this->formatArrayRequest($key, $value);
                } elseif (null !== $value && !is_array($value)) {
                    if ($value instanceof \DateTime) {
                        $value = $value->format('Y-m-d H:i:s');
                    }
                    $toImplode[] = $key.'='.urlencode((string)$value);
                }
            }
        }

        return implode('&', $toImplode);
    }

    /**
     * Converts an array into a request string
     *
     * @param string $key the array key to be used
     * @param array  $array the array to requestify
     *
     * @return string
     */
    private function formatArrayRequest($key, $array)
    {
        $toImplode = array();
        foreach ($array as $index => $value)
        {
            $toImplode[] = sprintf('%s[%s]=%s',
                $key,
                $index,
                urlencode((string)$value)
            );
        }

        return implode('&',$toImplode);
    }

    /**
     * @param string $str camelCased string
     *
     * @return string underscored string
     */
    public static function getUnderscoredString($str) {
      $str[0] = strtolower($str[0]);
      $func = create_function('$c', 'return "_" . strtolower($c[1]);');
      return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}