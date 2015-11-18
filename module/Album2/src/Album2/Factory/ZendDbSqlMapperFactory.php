<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.11.2015
 * Time: 13:33
 */

namespace Album2\Factory;

use Album2\Mapper\ZendDbSqlMapper;
use Album2\Model\Album;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
class ZendDbSqlMapperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(false),
            new Album()
        );
    }
}