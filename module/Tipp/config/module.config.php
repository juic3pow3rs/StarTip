<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Tipp\Service\TippServiceInterface' => 'Tipp\Factory\TippServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Tipp\Mapper\TippMapperInterface' => 'Tipp\Factory\ZendDbSqlMapperFactory',
            'Tipp\Form\UpdateZusatztippForm' => 'Tipp\Factory\UpdateZusatztippFormFactory',
            'Tipp\Form\ZusatztippForm' => 'Tipp\Factory\ZusatztippFormFactory',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Tipp\Controller\List'  => 'Tipp\Factory\ListControllerFactory',
            'Tipp\Controller\Write' => 'Tipp\Factory\WriteControllerFactory',
           
        )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "album-default"
            'tipp' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/album2" as uri
                    'route'    => '/tipp',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Tipp\Controller\List',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/:t_id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                't_id' => '\d*'
                            )
                        )
                    ),
                 
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/edit/:t_id',
                            'defaults' => array(
                                'controller' => 'Tipp\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                't_id' => '\d+'
                            )
                        )
                    ),
                		
                		'add' => array(
                				'type' => 'segment',
                				'options' => array(
                						'route'    => '/add/:s_id',
                						'defaults' => array(
                								'controller' => 'Tipp\Controller\Write',
                								'action'     => 'add'
                						),
                						'constraints' => array(
                								's_id' => '\d+'
                						)
                				)
                		),
                		
                    'update' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/update',
                            'defaults' => array(
                                'controller' => 'Tipp\Controller\Write',
                                'action'     => 'updateZusatztipp'
                            ),
                        )
                    ),
                    'addzusatz' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/addzusatz',
                            'defaults' => array(
                                'controller' => 'Tipp\Controller\Write',
                                'action'     => 'addZusatztipp'
                            ),
                        )
                    ),
                    'setzusatz' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/setzusatz',
                            'defaults' => array(
                                'controller' => 'Tipp\Controller\Write',
                                'action'     => 'setZusatztipp'
                            ),
                        )
                    ),
                )
            )
        )
    )
);