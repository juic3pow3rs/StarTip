<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $em           = $eventManager->getSharedManager();

        $zfcServiceEvents = $e->getApplication()->getServiceManager()->get('zfcuser_user_service')->getEventManager();

        // To validate new field
        $em->attach(
            'ZfcUser\Form\RegisterFilter',
            'init',
            function($e) {
                $filter = $e->getTarget();
                $filter->add(array(
                    'name'       => 'website',
                    'required'   => true,
                    'allowEmpty' => false,
                    'filters'    => array(array('name' => 'StringTrim')),
                    'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                        )
                    ),
                ));
            }
        );

        $em->attach(
            'ZfcUser\Form\Register',
            'init',
            function($e)
            {
                /* @var $form \ZfcUser\Form\Register */
                $form = $e->getTarget();
                $form->add(
                    array(
                        'name' => 'username',
                        'options' => array(
                            'label' => 'Username',
                        ),
                        'attributes' => array(
                            'type'  => 'text',
                        ),
                    )
                );

                $form->add(
                    array(
                        'name' => 'website',
                        'options' => array(
                            'label' => 'Website',
                        ),
                        'attributes' => array(
                            'type'  => 'text',
                        ),
                    )
                );
            }
        );

        // Store the field
        $zfcServiceEvents->attach('register', function($e) {
            $form = $e->getParam('form');
            $user = $e->getParam('user');

            /* @var $user \FooUser\Entity\User */
            $user->setUsername( $form->get('username')->getValue() );
            $user->setWebsite( $form->get('website')->getValue() );
        });
    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
