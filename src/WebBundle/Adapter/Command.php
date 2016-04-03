<?php

namespace WebBundle\Adapter;

use Application\Command\AbstractCommand as ApplicationCommand;

class Command extends ApplicationCommand
{
    protected static function fixCriteria($entityName, $criteria, $locale)
    {
        $result = array();

        if (!is_array($criteria)) {
            $criteria = json_decode($criteria, true);
        }

        if (is_array($criteria)) {

            if (!isset($criteria['comparison']) || $criteria['comparison'] != 'OR') {
                $result['comparison'] = 'AND';
            } else {
                $result['comparison'] = $criteria['comparison'];
            }

            $result['filters'] = array();

            if (isset($criteria['filters']) && is_array($criteria['filters'])) {
                foreach ($criteria['filters'] as $filter) {

                    if (isset($filter['field']) && isset($filter['type'])) {

                        $result['filters'][] = array(
                            'type' => $filter['type'],
                            'field' => self::fixField($entityName, $filter['field'], $locale),
                            'value' => (isset($filter['value'])) ? $filter['value'] : null,
                            'comparison' => (isset($filter['comparison'])) ? $filter['comparison'] : null
                        );

                    } elseif (isset($filter['criteria'])) {

                        $result['filters'][] = array(
                            'criteria' => self::fixCriteria($entityName, $filter['criteria'], $locale)
                        );

                    }

                }
            }
        }

        return $result;
    }
}
