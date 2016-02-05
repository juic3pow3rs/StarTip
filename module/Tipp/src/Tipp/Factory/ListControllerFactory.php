<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 16:04
 */

namespace Tipp\Factory;

use Tipp\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ListControllerFactory
 * @package Tipp\Factory
 */
class ListControllerFactory implements FactoryInterface
{
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|ListController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $tippService       = $realServiceLocator->get('Tipp\Service\TippServiceInterface');
        $spielService       = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');
        $mannschaftService  = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');

        return new ListController($tippService, $spielService, $mannschaftService);
    }
}