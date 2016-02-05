<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:24
 */

namespace Benutzer\Factory;

use Benutzer\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WriteControllerFactory
 * @package Benutzer\Factory
 */
class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
       	$benutzerService = $realServiceLocator->get('Benutzer\Service\BenutzerServiceInterface');
       	$suchBenutzerForm = $realServiceLocator->get('FormElementManager')->get('Benutzer\Form\SuchBenutzerForm');
       	$pictureForm = $realServiceLocator->get('FormElementManager')->get('Benutzer\Form\PictureForm');

        return new WriteController(
           	$benutzerService,
            $suchBenutzerForm,
            $pictureForm
        );
    }
}