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
                if (is_array($value) && count($value)) {
                    $date[] = $this->formatArrayRequest($key, $value);
                } elseif (null !== $value && !is_array($value)) {
                    if (false !== strpos($key, 'Date')) {
                        /* @var $value \DateTime */
                        if ($value->format('His') == 0) {
                            $value = $value->format('Y-m-d');
                        } else {
                            $value = $value->format('Y-m-d H:i:s');
                        }
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
        foreach ($array as $value)
        {
            $toImplode[] = $key.'='.urlencode((string)$value);
        }

        return implode('&',$toImplode);
    }
}