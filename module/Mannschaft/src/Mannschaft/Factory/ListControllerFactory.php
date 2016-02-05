<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 16:04
 */

namespace Mannschaft\Factory;

use Mannschaft\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ListControllerFactory
 * @package Mannschaft\Factory
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
        $mannschaftService       = $realServiceLocator->get('Mannschaft\Service\MannschaftServiceInterface');

        return new ListController($mannschaftService);
    }
}