<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:29
 */

namespace Post\Factory;

use Post\Service\PostService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PostService(
            $serviceLocator->get('Post\Mapper\PostMapperInterface')
        );
    }
}