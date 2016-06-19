<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Produtos\Controller\Produtos'              => 'Produtos\Controller\ProdutosController',
            'Produtos\Controller\Categorias'          => 'Produtos\Controller\CategoriasController',
            'Produtos\Controller\Estoques'            => 'Produtos\Controller\EstoquesController',
            'Produtos\Controller\Promocoes'            => 'Produtos\Controller\PromocoesController',
        ),

    ),
    
    'router' => array(
        'routes' => array(
            'produtos' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/produtos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Produtos\Controller',
                        'controller' => 'Produtos',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array(//permite mandar dados pela url 
                            'wildcard' => array(
                                'type' => 'Wildcard'
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
            'Session' => function($sm) {
                return new Zend\Session\Container('Session');
            },
            'Auth\Service\Auth' => function(){
              return new Auth\Service\Auth();  
            },
            'Produtos\Service\Produto' => function(){
              return new Produtos\Service\ProdutosService();  
            },
            'Produtos\Service\Estoque' => function(){
              return new Produtos\Service\EstoquesService();  
            },
            'Produtos\Service\Promocao' => function(){
              return new Produtos\Service\PromocoesService();  
            },
            'Produtos\Service\Categoria' => function(){
              return new Produtos\Service\CategoriasService();  
            },
	    'Core\Acl\Builder' => function(){
              return new Core\Acl\Builder();  
            },
                    
        ),
    ),
              
    'doctrine' => array(
//        'authentication' => array(
//            'orm_default' => array(
//                'object_manager' => 'Doctrine\ORM\EntityManager',
//                'identity_class' => 'Auth\Model\Usuario',
//                'identity_property' => 'email',
//                'credential_property' => 'senha',
//            ),
//        ),
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Produtos/Model')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Produtos\Model' => 'application_entities'
                )
            )
        )
    ),
                    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout2.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
