<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 13:33
 */

namespace Mannschaft\Factory;

use Mannschaft\Mapper\ZendDbSqlMapper;
use Mannschaft\Model\Mannschaft;
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
            new Mannschaft()
        );
    }
}