<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Gruppe\Service\GruppeServiceInterface' => 'Gruppe\Factory\GruppeServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Gruppe\Mapper\GruppeMapperInterface' => 'Gruppe\Factory\ZendDbSqlMapperFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Gruppe\Controller\List'  => 'Gruppe\Factory\ListControllerFactory',
            'Gruppe\Controller\Write' => 'Gruppe\Factory\WriteControllerFactory',
         
        )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "album-default"
            'gruppe' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/gruppe" as uri
                    'route'    => '/gruppe',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Gruppe\Controller\List',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/:g_id',
                            'defaults' => array(
                                'action' => 'detail'
                            ),
                            'constraints' => array(
                                'g_id' => '\d*'
                            )
                        )
                    ),

                    'show' => array(
                		'type' => 'literal',
                		'options' => array(
                			'route' => '/show',
                			'defaults' => array(
                				'controller' => 'Gruppe\Controller\List',
                				'action'     => 'show'
                			)
                		)
                	),
                    'compare' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/compare/:g_id',
                            'defaults' => array(
                                'controller' => 'Gruppe\Controller\List',
                                'action'     => 'compare'
                            ),
                            'constraints' => array(
                                'g_id' => '\d+'
                            )
                        )
                    ),
                		
                	'annehmen' => array(
                	    'type' => 'segment',
                		'options' => array(
                			'route' => '/annehmen/:g_id',
                			'defaults' => array(
                				'controller' => 'Gruppe\Controller\List',
                				'action'     => 'annehmen'
                			),
                			'constraints' => array(
                				'g_id' => '\d+'
                			)
                		)
                	),
                		
                	'ablehnen' => array(
                	    'type' => 'segment',
                		'options' => array(
                	    	'route' => '/ablehnen/:g_id',
                		    'defaults' => array(
                			    'controller' => 'Gruppe\Controller\List',
                			    'action'     => 'ablehnen'
                		    ),
                		    'constraints' => array(
                			    'g_id' => '\d+'
                		    )
                		)
                    ),
                    'add' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Gruppe\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                		
                	
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/edit/:g_id',
                            'defaults' => array(
                                'controller' => 'Gruppe\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                'g_id' => '\d+'
                            )
                        )
                    ),
                		
                    'invite' => array(
                		'type' => 'segment',
                		'options' => array(
                            'route' => '/invite/:id',
                			'defaults' => array(
                				'controller' => 'Gruppe\Controller\Write',
                				'action'     => 'invite'
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