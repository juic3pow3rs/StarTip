<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 16:04
 */

namespace Album2\Factory;

use Album2\Controller\ListController;
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
        $albumService       = $realServiceLocator->get('Album2\Service\AlbumServiceInterface');

        return new ListController($albumService);
    }
}