<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:08
 */

namespace Benutzer;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\Date;

/**
 * Class Module
 * @package Benutzer
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface
{

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $em           = $eventManager->getSharedManager();

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        // Filter f�r das Feld Geburtsdatum, welches an die RegisterForm angeh�ngt werden soll
        $em->attach(
            'ZfcUser\Form\RegisterFilter',
            'init',
            function($e) {
                $filter = $e->getTarget();
                $filter->add(array(
                    'name'       => 'geburtsdatum',
                    'required'   => true,
                    'allowEmpty' => false,
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        ),
                    $date = new Date()
                    ),
                ));
            }
        );

        // Feld Geburtsdatum an die RegisterForm anh�ngen
        $em->attach(
            'ZfcUser\Form\Register',
            'init',
            function($e)
            {
                // @var $form \ZfcUser\Form\Register
                $form = $e->getTarget();
                $form->add(
                    array(
                        'name' => 'geburtsdatum',
                        'options' => array(
                            'label' => 'Geburtsdatum',
                        ),
                        'attributes' => array(
                            'type'  => 'text',
                        ),
                    )
                );
            }
        );

        // Das Feld im Objekt abspeichern
        $zfcServiceEvents->attach('register', function($e) {
            $form = $e->getParam('form');
            $user = $e->getParam('user');

            // @var $user \Benutzer\Model\User
            $user->setGeburtsdatum( $form->get('geburtsdatum')->getValue() );
        });

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //You need a copy of the service manager and it has to be set as a member for the lambda function to call it
        $this->sm = $e->getApplication()->getServiceManager();

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        $zfcServiceEvents->attach('register.post', function($e) {

            /** @var \Benutzer\Model\User $user */
            $user = $e->getParam('user');

            //bjyAuthorize uses a magic constant for the table name
            $action = new \Zend\Db\Sql\Insert('user_role_linker');
            $action->columns(array('user_id', 'role_id'));
            $action->values(array('user_id' => $user->getId(), 'role_id' => 2), $action::VALUES_MERGE);

            //This is the adapter that both bjyAuthorize and zfcuser use
            $adapter = $this->sm->get('zfcuser_zend_db_adapter');
            //Build the insert statement
            $sql = new \Zend\Db\Sql\Sql($adapter);
            //Prepare Statement
            $stmt = $sql->prepareStatementForSqlObject($action);

            //Execute the insert statement
            $stmt->execute();
        });
    }
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
                    // Autoload all classes from namespace 'Benutzer' from '/module/Benutzer/src/Benutzer'
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
}