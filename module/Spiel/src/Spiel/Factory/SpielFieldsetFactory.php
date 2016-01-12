<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 14.12.2015
 * Time: 15:44
 */

namespace Spiel\Factory;

use Spiel\Form\SpielFieldset;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
class SpielFieldsetFactory implements FactoryInterface
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
        $mannschaftServiceInterface = $serviceLocator->get('Mannschaft\Service\MannschaftServiceInterface');

        return new SpielFieldset(null, null, $mannschaftServiceInterface);
    }
}