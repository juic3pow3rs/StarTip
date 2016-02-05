<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 06.12.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Mannschaft\Service\MannschaftServiceInterface' => 'Mannschaft\Factory\MannschaftServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Mannschaft\Mapper\MannschaftMapperInterface' => 'Mannschaft\Factory\ZendDbSqlMapperFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Mannschaft\Controller\List'  => 'Mannschaft\Factory\ListControllerFactory',
            'Mannschaft\Controller\Write' => 'Mannschaft\Factory\WriteControllerFactory',
           
        )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "mannschaft-default"
            'mannschaft' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/mannschaft" as uri
                    'route'    => '/mannschaft',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Mannschaft\Controller\List',
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
                                'controller' => 'Mannschaft\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Mannschaft\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'crawl' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/crawl',
                            'defaults' => array(
                                'controller' => 'Mannschaft\Controller\Write',
                                'action'     => 'crawl'
                            ),
                        )
                    ),
                )
            )
        )
    )
);