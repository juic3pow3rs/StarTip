<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 05.02.2016
 * Time: 15:09
 */

namespace Application\Factory;

use Application\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IndexControllerFactory
 * @package Application\Factory
 */
class IndexControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return IndexController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $spielService = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');
        $gruppeService = $realServiceLocator->get('Gruppe\Service\GruppeServiceInterface');

        return new IndexController(
            $spielService,
            $gruppeService
        );
    }
}