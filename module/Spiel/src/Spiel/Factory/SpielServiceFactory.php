<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:29
 */

namespace Spiel\Factory;

use Spiel\Service\SpielService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SpielServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SpielService(
            $serviceLocator->get('Spiel\Mapper\SpielMapperInterface')
        );
    }
}