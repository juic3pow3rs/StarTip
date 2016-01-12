<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:37
 */

namespace Gruppe\Factory;

use Gruppe\Service\GruppeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GruppeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new GruppeService(
            $serviceLocator->get('Gruppe\Mapper\GruppeMapperInterface')
        );
    }
}