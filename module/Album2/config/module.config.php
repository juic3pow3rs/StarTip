<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 03.11.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Album2\Service\AlbumServiceInterface' => 'Album2\Factory\AlbumServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Album2\Mapper\AlbumMapperInterface' => 'Album2\Factory\ZendDbSqlMapperFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Album2\Controller\List'  => 'Album2\Factory\ListControllerFactory',
            'Album2\Controller\Write' => 'Album2\Factory\WriteControllerFactory',
            'Album2\Controller\Delete' => 'Album2\Factory\DeleteControllerFactory'
        )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "album-default"
            'album2' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/album2" as uri
                    'route'    => '/album2',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Album2\Controller\List',
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
                                'controller' => 'Album2\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/edit/:id',
                            'defaults' => array(
                                'controller' => 'Album2\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'Album2\Controller\Delete',
                                'action'     => 'delete'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                )
            )
        )
    )
);