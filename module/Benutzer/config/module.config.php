<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:09
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Benutzer\Service\BenutzerServiceInterface' => 'Benutzer\Factory\BenutzerServiceFactory',
            'Zend\Db\Adapter\Adapter'    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Benutzer\Mapper\BenutzerMapperInterface' => 'Benutzer\Factory\ZendDbSqlMapperFactory'
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'Benutzer\Controller\List' => 'Benutzer\Factory\ListControllerFactory',
        	'Benutzer\Controller\Write' => 'Benutzer\Factory\WriteControllerFactory'
        )
    ),

    // This lines opens the configuration for the RouteManager
    'router' => array(
        // Open configuration for all possible routes
        'routes' => array(
            // Create a new route called "album-default"
            'user' => array(
                // Define the routes type to be "Zend\Mvc\Router\Http\Literal", which is basically just a string
                'type' => 'literal',
                // Configure the route itself
                'options' => array(
                    // Listen to "/user" as uri
                    'route'    => '/user',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'list' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/list',
                            'defaults' => array(
                                'controller' => 'Benutzer\Controller\List',
                                'action'     => 'list'
                            )
                        )
                    ),
                	 
                    'detail' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route'    => '/detail/:id',
                            'defaults' => array(
                                'controller' => 'Benutzer\Controller\List',
                                'action'     => 'detail'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    		),
                    'suche' => array(
                		'type' => 'literal',
                		'options' => array(
                			'route' => '/suche',
                			'defaults' => array(
                				'controller' => 'Benutzer\Controller\Write',
                				'action'     => 'suche'
                            )
                        )
                    ),
                    'avatar' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/avatar',
                            'defaults' => array(
                                'controller' => 'Benutzer\Controller\Write',
                                'action' => 'avatarUpload'
                            )
                        )
                    ),
                    'picture' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/picture',
                            'defaults' => array(
                                'controller' => 'Benutzer\Controller\Write',
                                'action' => 'profilePicture'
                            )
                        )
                    ),
                )
            )
        )
    )
);