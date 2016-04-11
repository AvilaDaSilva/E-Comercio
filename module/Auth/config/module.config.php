<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => 'Auth\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'auth' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'module'        => 'auth'
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
                        'child_routes' => array( //permite mandar dados pela url
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),

                ),
            ),
        ),
    ),
    'view_manager' => array( //the module can have a specific layout
         'template_map' => array(
             'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
         ),
        'template_path_stack' => array(
            'auth' => __DIR__ . '/../view',
        ),
    ),
//    'db' => array( //module can have a specific db configuration
//        'driver' => 'PDO_SQLite',
//        'dsn' => 'sqlite:' . __DIR__ .'/../data/banco.db',
//        'driver_options' => array(
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
//        )
//    )
);