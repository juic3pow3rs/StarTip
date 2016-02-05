<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 12:10
 */

namespace Spiel;

use Spiel\Model\Spiel;
use Spiel\Form\SpielFieldset;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

/**
 * Class Module
 * @package Spiel
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface {

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // Autoload all classes from namespace 'Spiel' from '/module/Spiel/src/Spiel'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'SpielTableGateway' => function($serviceLocator) {
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

                    $resultSet = new HydratingResultSet();
                    $resultSet->setHydrator(new ClassMethods());
                    $resultSet->setObjectPrototype(new Spiel());

                    return new TableGateway('spiel', $dbAdapter, null, $resultSet);
                },
            )
        );
    }
/*
    public function getFormElementConfig()
    {
        return array(
            'factories' => array(
                'Spiel\Form\InsertSpielFieldset' => function($serviceLocator) {

                    $mannschaftServiceInterface = $serviceLocator->get('Mannschaft\Service\MannschaftServiceInterface');

                    return new SpielFieldset(null, null, $mannschaftServiceInterface);
                }
            )
        );
    }
*/
}


