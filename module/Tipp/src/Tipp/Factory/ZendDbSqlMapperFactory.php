<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 13:33
 */

namespace Tipp\Factory;

use Tipp\Mapper\ZendDbSqlMapper;
use Tipp\Model\Tipp;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class ZendDbSqlMapperFactory
 * @package Tipp\Factory
 */
class ZendDbSqlMapperFactory implements FactoryInterface
{
   /**
    * 
    * @param ServiceLocatorInterface $serviceLocator
    * @return \Tipp\Mapper\ZendDbSqlMapper
    */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ZendDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(false),
            new Tipp()
        );
    }
}