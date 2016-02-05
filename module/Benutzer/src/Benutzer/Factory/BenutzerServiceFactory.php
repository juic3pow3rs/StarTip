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

/**
 * Class BenutzerServiceFactory
 * @package Benutzer\Factory
 */
class BenutzerServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return BenutzerService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BenutzerService(
            $serviceLocator->get('Benutzer\Mapper\BenutzerMapperInterface')
        );
    }
}