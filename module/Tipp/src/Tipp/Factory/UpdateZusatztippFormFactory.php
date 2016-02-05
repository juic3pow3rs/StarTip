<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 22.12.2015
 * Time: 21:58
 */

namespace Tipp\Factory;

use Tipp\Form\UpdateZusatztippForm;
use Tipp\Service\TippServiceInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpdateZusatztippFormFactory
 * @package Tipp\Factory
 */
class UpdateZusatztippFormFactory implements FactoryInterface
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
        $tippServiceInterface = $serviceLocator->get('Tipp\Service\TippServiceInterface');

        return new UpdateZusatztippForm(null, null, $tippServiceInterface);
    }
}