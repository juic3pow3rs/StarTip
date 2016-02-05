<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 03.11.2015
 * Time: 11:05
 */

namespace Album2;

use Album2\Model\Album;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class Module
 * @package Album2
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
                    // Autoload all classes from namespace 'Album2' from '/module/Album2/src/Album2'
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
                'AlbumTableGateway' => function($serviceLocator) {
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');

                    $resultSet = new HydratingResultSet();
                    $resultSet->setHydrator(new ClassMethods());
                    $resultSet->setObjectPrototype(new Album());

                    return new TableGateway('album', $dbAdapter, null, $resultSet);
                }
            )
        );
    }

}

