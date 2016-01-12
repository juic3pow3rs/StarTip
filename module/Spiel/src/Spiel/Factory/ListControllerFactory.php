<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:32
 */

namespace Spiel\Factory;

use Spiel\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
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
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $spielService       = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');
        $mannschaftService  = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');
        $tippService        = $realServiceLocator->get('Tipp\Service\TippServiceInterface');
       
       

        return new ListController($spielService, $mannschaftService, $tippService );
    }
}