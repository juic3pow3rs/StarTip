<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Spiel\Service\SpielServiceInterface' => 'Spiel\Factory\SpielServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Spiel\Mapper\SpielMapperInterface' => 'Spiel\Factory\ZendDbSqlMapperFactory',
            'Spiel\Form\SpielFieldset' => 'Spiel\Factory\SpielFieldsetFactory',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Spiel\Controller\List'  => 'Spiel\Factory\ListControllerFactory',
            'Spiel\Controller\Write' => 'Spiel\Factory\WriteControllerFactory',
          )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "spiel-default"
            'spiel' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/spiel" as uri
                    'route'    => '/spiel',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Spiel\Controller\List',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/:id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'id' => '\d*'
                            )
                        )
                    ),
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Spiel\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Spiel\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'erg' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/erg/:id',
                            'defaults' => array(
                                'controller' => 'Spiel\Controller\Write',
                                'action'     => 'erg'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'crawl' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/crawl',
                            'defaults' => array(
                                'controller' => 'Spiel\Controller\Write',
                                'action'     => 'crawl'
                            )
                        )
                    ),
                )
            ),
        )
    )
);