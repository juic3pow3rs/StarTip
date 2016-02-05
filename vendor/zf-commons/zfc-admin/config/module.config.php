<?php
/**
 * Copyright (c) 2012 Jurian Sluiman.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     ZfcAdmin
 * @subpackage  Controller
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Jurian Sluiman.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://zf-commons.github.com
 */

return array(
    'controllers' => array(
        'factories' => array(
            'ZfcAdmin\Controller\AdminController' => 'ZfcAdmin\Factory\AdminControllerFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'zfcadmin' => array(
        'use_admin_layout'      => true,
        'admin_layout_template' => 'layout/admin',
    ),

    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        ),
    ),

    'navigation' => array(
        'admin' => array(
            array(
                'label' => 'Home',
                'route' => 'home',
            ),
            array(
                'label' => 'Dashboard',
                'route' => 'zfcadmin',
            ),
            array(
                'label' => 'Mannschaft',
                'route' => 'mannschaft',
                'pages' => array(
                    array(
                        'label' => 'Hinzufuegen',
                        'route' => 'mannschaft/add',
                        'resource' => 'controller/Mannschaft\Controller\Write'
                    ),
                )
            ),
            array(
                'label' => 'Spiel',
                'route' => 'mannschaft',
                'pages' => array(
                    array(
                        'label' => 'Hinzufuegen',
                        'route' => 'spiel/add',
                        'resource' => 'controller/Spiel\Controller\Write'
                    ),
                )
            ),
            array(
                'label' => 'Zusatztipp',
                'route' => 'tipp',
                'pages' => array(
                    array(
                        'label' => 'Aktualisieren',
                        'route' => 'tipp/update',
                        'resource' => 'controller/Mannschaft\Controller\Write'
                    ),
                    array(
                        'label' => 'Ergebnis eintragen',
                        'route' => 'tipp/setzusatz',
                        'resource' => 'controller/Mannschaft\Controller\Write'
                    ),
                )
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'ZfcAdmin\Controller\AdminController',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'activate' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/activate',
                            'defaults' => array(
                                'controller' => 'ZfcAdmin\Controller\AdminController',
                                'action' => 'activate'
                            )
                        ),
                    ),
                    'reset' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/reset',
                            'defaults' => array(
                                'controller' => 'ZfcAdmin\Controller\AdminController',
                                'action' => 'reset'
                            )
                        ),
                    ),
                    'modus' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route'    => '/modus',
                            'defaults' => array(
                                'controller' => 'ZfcAdmin\Controller\AdminController',
                                'action' => 'modus'
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),

    

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
);