<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 28.12.2015
 * Time: 21:30
 */

namespace Tipp\Factory;

use Tipp\Form\ZusatztippForm;
use Tipp\Service\TippServiceInterface;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ZusatztippFormFactory
 * @package Tipp\Factory
 */
class ZusatztippFormFactory implements FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Tipp\Form\ZusatztippForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $mannschaftServiceInterface = $serviceLocator->get('Mannschaft\Service\MannschaftServiceInterface');
        $tippServiceInterface = $serviceLocator->get('Tipp\Service\TippServiceInterface');

        return new ZusatztippForm(null, null, $mannschaftServiceInterface, $tippServiceInterface);
    }
}