<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 15.12.2015
 * Time: 17:24
 */

namespace Tipp\Factory;

use Tipp\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $tippService = $realServiceLocator->get('Tipp\Service\TippServiceInterface');
        $tippInsertForm = $realServiceLocator->get('FormElementManager')->get('Tipp\Form\InsertTippForm');
        $updateZusatztippForm = $realServiceLocator->get('FormElementManager')->get('Tipp\Form\UpdateZusatztippForm');
        $zusatztippForm = $realServiceLocator->get('FormElementManager')->get('Tipp\Form\ZusatztippForm');

        return new WriteController(
            $tippService,
            $tippInsertForm,
            $updateZusatztippForm,
            $zusatztippForm
        );
    }
}