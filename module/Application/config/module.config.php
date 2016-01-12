<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home',
            ),
            array(
                'label' => 'User',
                'route' => 'user',
                'pages' => array(
                    array(
                        'label' => 'Profil',
                        'route' => 'zfcuser',
                        'resource' => 'controller/zfcuser:index'
                    ),
                    array(
                        'label' => 'Login',
                        'route' => 'zfcuser/login',
                        'resource' => 'controller/zfcuser:login'
                    ),
                    array(
                        'label' => 'Register',
                        'route' => 'zfcuser/register',
                        'resource' => 'controller/zfcuser:register'
                    ),
                    array(
                        'label' => 'Logout',
                        'route' => 'zfcuser/logout',
                        'resource' => 'controller/zfcuser:logout'
                    ),
                )
            ),
            array(
                'label' => 'Spiele',
                'route' => 'spiel',
                'resource' => 'controller/Spiel\Controller\List',
            ),
            array(
                'label' => 'Mannschaften',
                'route' => 'mannschaft',
                'resource' => 'controller/Mannschaft\Controller\List'
            ),
            array(
                'label' => 'Rangliste',
                'route' => 'user/list',
                'resource' => 'controller/Benutzer\Controller\List'
            ),
            array(
                'label' => 'Tippgemeinschaften',
                'route' => 'gruppe',
                'resource' => 'controller/Gruppe\Controller\List',
                'pages' => array(
                    array(
                        'label' => 'Erstellen',
                        'route' => 'gruppe/add',
                    ),
                    array(
                        'label' => 'Meine TG',
                        'route' => 'gruppe',
                    ),
                    array(
                        'label' => 'Einladungen',
                        'route' => 'gruppe/show'
                    ),
                )
            ),
            array(
                'label' => 'Zusatztipp',
                'route' => 'tipp/addzusatz',
                'resource' => 'controller/Tipp\Controller\Write:add'
            ),
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'htimg' => [
        'filters' => [
            'my_thumbnail' => [ // this is  filter service
                'type' => 'thumbnail', // this is a filter loader
                'options' => [  // filter loader passes these options to a Filter which manipulates the image
                    'width' => 100,
                    'height' => 100,
                    //'format' => 'jpeg' // format is optional and defaults to the format of given image
                ]
            ]
        ]
    ],
);
