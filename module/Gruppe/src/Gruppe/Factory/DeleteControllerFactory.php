<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.11.2015
 * Time: 16:22
 */

namespace Gruppe\Factory;

use Gruppe\Controller\DeleteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DeleteControllerFactory
 * @package Gruppe\Factory
 */
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
        $gruppeService       = $realServiceLocator->get('Gruppe\Service\GruppeServiceInterface');

        return new DeleteController($gruppeService);
    }
}