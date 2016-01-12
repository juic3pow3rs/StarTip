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

class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $mannschaftService = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');
        $mannschaftInsertForm = $realServiceLocator->get('FormElementManager')->get('Mannschaft\Form\InsertMannschaftForm');

        return new WriteController(
            $mannschaftService,
            $mannschaftInsertForm
        );
    }
}