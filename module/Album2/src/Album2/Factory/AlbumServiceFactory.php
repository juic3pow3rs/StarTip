<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.11.2015
 * Time: 16:37
 */

namespace Album2\Factory;

use Album2\Service\AlbumService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AlbumServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AlbumService(
            $serviceLocator->get('Album2\Mapper\AlbumMapperInterface')
        );
    }
}