<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:04
 */

namespace Gruppe\Factory;

use Gruppe\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ListControllerFactory
 * @package Gruppe\Factory
 */
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
        $gruppeService       = $realServiceLocator->get('Gruppe\Service\GruppeServiceInterface');

        return new ListController($gruppeService);
    }
}