<?php

namespace Application\Command;

use Application\Adapter\ArrayCollection;
use Application\Command\ListCommand as ApplicationListCommand;
use Application\Contract\Command as CommandInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCommand implements CommandInterface
{
    protected $_validate = true;

    public static function create(Request $request, $commandClass = null)
    {
        if (!$commandClass) {
            $commandClass = $request->attributes->get('_commandClass');
        }

        if (!$commandClass) {
            return;
        }

        $commandClass = '\\Application\\Command\\' . $commandClass . 'Command';

        switch (static::getType($commandClass)) {
            case 'List':
                $entityName = static::getEntityName($commandClass);

                return ApplicationListCommand::fromArray(
                    $commandClass,
                    array_merge(
                        $request->query->all(),
                        $request->request->all(),
                        array(
                            'criteria' => static::fixCriteria($entityName, $request->query->get('criteria'), 'ru'),
                            'order' => static::fixOrder($entityName, $request->query->get('order'), 'ru'),
                        )
                    )
                );
                break;
            default:
                return static::fromArray(
                    $commandClass,
                    array_merge(
                        $request->query->all(),
                        $request->request->all(),
                        $request->attributes->all()
                    )
                );
        }
    }

    protected static function getType($commandClass)
    {
        if (preg_match('/(.*)\\\\(.*)Command/', $commandClass, $matches)) {
            return $matches[2];
        }
    }

    protected static function getEntityName($commandClass)
    {
        return explode('\\', $commandClass)[3];
    }

    public static function fromArray($class, $attributes = array())
    {
        $reflectionClass = new \ReflectionClass($class);

        $data = array_merge(
            $reflectionClass->getDefaultProperties(),
            $attributes
        );

        $args = array();

        $constructor = $reflectionClass->getConstructor();
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $value = $data[$param->getName()];
                $args[] = ($value !== '') ? $value : null;
            }
        }

        return $reflectionClass->newInstanceArgs($args);
    }

    protected static function fixCriteria($entityName, $criteria, $locale)
    {
    }

    protected static function fixOrder($entityName, $order, $locale)
    {
        $result = array();

        if (!is_array($order)) {
            $order = json_decode($order, true);
        }

        if (is_array($order)) {
            foreach ($order as $row) {
                $result[] = array(
                    'field' => static::fixField($entityName, $row['property'], $locale),
                    'direction' => $row['direction']
                );
            }
        }

        return $result;
    }

    protected static function fixField($entityName, $name, $locale)
    {
        $name = explode(',', $name);
        $result = null;

        foreach ($name as $field) {
            $result .= $result ? ',' : '';
            $result .= (strpos($field, '#')) ? str_replace('#', '.', $field) : $entityName . '.' . $field;
        }

        $result = static::fixFieldLocale($result, $locale);

        return $result;
    }

    public static function fixFieldLocale($field, $locale)
    {
        list($entity, $property) = explode('.', $field);

        $class = 'Domain\\Entity\\' . $entity;

        if (class_exists($class) && !property_exists($class, $property)) {
            return $field . '_' . $locale;
        }

        return $field;
    }

    public static function getFileFields()
    {
        return [];
    }

    public function isValidated()
    {
        return $this->_validate;
    }

    public function getValidationGroups()
    {
        return null;
    }

    //создаем комманду по массиву параметров

    public function getData()
    {
        return new ArrayCollection();
    }
}
