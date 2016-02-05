<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 17:24
 */

namespace Mannschaft\Factory;

use Mannschaft\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WriteControllerFactory
 * @package Mannschaft\Factory
 */
class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $mannschaftService = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');
        $mannschaftInsertForm = $realServiceLocator->get('FormElementManager')->get('Mannschaft\Form\InsertMannschaftForm');
        $spielService = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');

        return new WriteController(
            $mannschaftService,
            $mannschaftInsertForm,
            $spielService
        );
    }
}