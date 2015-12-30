<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:29
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Post\Service\PostServiceInterface' => 'Post\Factory\PostServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Post\Mapper\PostMapperInterface' => 'Post\Factory\ZendDbSqlMapperFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Post\Controller\List'  => 'Post\Factory\ListControllerFactory',
            'Post\Controller\Write' => 'Post\Factory\WriteControllerFactory',
          )
    ),
    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "post-default"
            'post' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/spiel" as uri
                    'route'    => '/post',
                    // Define default controller and action to be called when this route is matched
                    'defaults' => array(
                        'controller' => 'Post\Controller\List',
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
                 
                		
                	'add' => array(
                		'type' => 'segment',
                		'options' => array(
                		'route'    => '/add/:g_id',
                		'defaults' => array(
                			'controller' => 'Post\Controller\Write',
                							'action'     => 'add'
                				),
                				'constraints' => array(
                				'g_id' => '\d+'
                			)
                		)
               	),
                	
            )
                
                   
                
            )
        )
    )
);