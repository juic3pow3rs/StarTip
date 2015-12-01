<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 15:50
 */

namespace Benutzer\Factory;

use Benutzer\Service\BenutzerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BenutzerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BenutzerService(
            $serviceLocator->get('Benutzer\Mapper\BenutzerMapperInterface')
        );
    }
}