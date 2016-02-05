<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 12:43
 */

namespace Spiel\Factory;

use Spiel\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WriteControllerFactory
 * @package Spiel\Factory
 */
class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $spielService = $realServiceLocator->get('Spiel\Service\SpielServiceInterface');
        $spielInsertForm = $realServiceLocator->get('FormElementManager')->get('Spiel\Form\InsertSpielForm');
        $tippService = $realServiceLocator->get('Tipp\Service\TippServiceInterface');
        $mannschaftService = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');

        return new WriteController(
            $spielService,
            $spielInsertForm,
            $tippService,
            $mannschaftService
        );
    }
}