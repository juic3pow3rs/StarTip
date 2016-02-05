<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 16:37
 */

namespace Tipp\Factory;

use Tipp\Service\TippService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TippServiceFactory
 * @package Tipp\Factory
 */
class TippServiceFactory implements FactoryInterface
{
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed|TippService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TippService(
            $serviceLocator->get('Tipp\Mapper\TippMapperInterface')
        );
    }
}