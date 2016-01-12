<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 17:24
 */

namespace Gruppe\Factory;

use Gruppe\Controller\WriteController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriteControllerFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return WriteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $gruppeService = $realServiceLocator->get('Gruppe\Service\GruppeServiceInterface');
        $gruppeInsertForm = $realServiceLocator->get('FormElementManager')->get('Gruppe\Form\InsertGruppeForm');
        $benutzerService = $realServiceLocator->get('Benutzer\Service\BenutzerServiceInterface');
        $gruppeInviteForm = $realServiceLocator->get('FormElementManager')->get('Gruppe\Form\InviteGruppeForm');

        return new WriteController(
            $gruppeService,
            $gruppeInsertForm,
            $benutzerService,
            $gruppeInviteForm
        );
    }
}