<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 05.01.2016
 * Time: 00:16
 */

namespace ZfcAdmin\Factory;

use ZfcAdmin\Controller\AdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AdminController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $spielService = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');
        $tippService = $realServiceLocator->get('Tipp\Service\TippServiceInterface');
        $mannschaftService = $realServiceLocator->get('Mannschaft\Service\MannschaftserviceInterface');

        return new AdminController(
            $spielService,
            $tippService,
            $mannschaftService
        );
    }
}