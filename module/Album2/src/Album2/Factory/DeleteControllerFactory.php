<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.11.2015
 * Time: 16:22
 */

namespace Album2\Factory;

use Album2\Controller\DeleteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DeleteControllerFactory implements FactoryInterface
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

        return new DeleteController($albumService);
    }
}