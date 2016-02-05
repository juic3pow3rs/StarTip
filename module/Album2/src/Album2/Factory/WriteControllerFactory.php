<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 15.11.2015
 * Time: 17:24
 */

namespace Album2\Factory;

use Album2\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class WriteControllerFactory
 * @package Album2\Factory
 */
class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $albumService = $realServiceLocator->get('Album2\Service\AlbumServiceInterface');
        $albumInsertForm = $realServiceLocator->get('FormElementManager')->get('Album2\Form\InsertAlbumForm');

        return new WriteController(
            $albumService,
            $albumInsertForm
        );
    }
}