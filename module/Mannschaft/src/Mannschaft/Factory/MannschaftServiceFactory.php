<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 14:00
 */

namespace Mannschaft\Factory;

use Mannschaft\Service\MannschaftService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MannschaftServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MannschaftService(
            $serviceLocator->get('Mannschaft\Mapper\MannschaftMapperInterface')
        );
    }
}