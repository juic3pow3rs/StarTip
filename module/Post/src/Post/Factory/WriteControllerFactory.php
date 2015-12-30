<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 12:43
 */

namespace Post\Factory;

use Post\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $postService = $realServiceLocator->get('Post\Service\PostServiceInterface');
        $postInsertForm = $realServiceLocator->get('FormElementManager')->get('Post\Form\InsertPostForm');

        return new WriteController(
            $postService,
            $postInsertForm
        );
    }
}