<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:32
 */

namespace Post\Factory;

use Post\Controller\ListController;
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
        $postService       = $realServiceLocator->get('Post\Service\PostServiceInterface');
       

        return new ListController($postService);
    }
}