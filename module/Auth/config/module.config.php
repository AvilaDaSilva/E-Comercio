<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Auth'              => 'Auth\Controller\AuthController',
            'Auth\Controller\Usuarios'          => 'Auth\Controller\UsuariosController',
            'Auth\Controller\ConverterXlsTxt'   => 'Auth\Controller\ConverterXlsTxtController',
            'Auth\Controller\Perfis'            => 'Auth\Controller\PerfisController',
            'Auth\Controller\UsuarioPerfil'     => 'Auth\Controller\UsuarioPerfilController',
            'Auth\Controller\SelecionarPerfil'  => 'Auth\Controller\SelecionarPerfilController',
            'Auth\Controller\Acao'              => 'Auth\Controller\AcaoController',
            'Auth\Controller\Programas'         => 'Auth\Controller\ProgramasController',
            'Auth\Controller\Filiais'           => 'Auth\Controller\FiliaisController',
            'Auth\Controller\ProgramasPerfis'   => 'Auth\Controller\ProgramasPerfisController',
            'Auth\Controller\Modulos'           => 'Auth\Controller\ModulosController',
            'Auth\Controller\Empresas'          => 'Auth\Controller\EmpresasController',
            'Auth\Controller\AcaoPerfil'        => 'Auth\Controller\AcaoPerfilController',
            'Auth\Controller\Logs'              => 'Auth\Controller\LogsController',
            'Auth\Controller\Mensagens'         => 'Auth\Controller\MensagensController',
            'Auth\Controller\Padrao'            => 'Auth\Controller\PadraoController',
        	'Auth\Controller\Noticias'            => 'Auth\Controller\NoticiasController',
        ),

    ),
      'router' => array(
        'routes' => array(
            'auth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller' => 'Auth',
                        'action' => 'index',
                        'module' => 'auth'
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
        'factories' => array(
            'Session' => function($sm) {
                return new Zend\Session\Container('Session');
            },
            'Auth\Service\Auth' => function(){
              return new Auth\Service\Auth();  
            },
             'Auth\Service\Usuario' => function(){
              return new Auth\Service\Usuario();  
            },
              'Auth\Service\perfil' => function(){
              return new Auth\Service\Perfil();  
            },
        ),
    ),
              
       'doctrine' => array(
            'authentication' => array(
                'orm_default' => array(
                    'object_manager' => 'Doctrine\ORM\EntityManager',
                    'identity_class' => 'Auth\Model\Usuario',
                    'identity_property' => 'login',
                    'credential_property' => 'senha',
                ),
        ),
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Auth/Model')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Auth\Model' => 'application_entities'
                )
            ))),
    'view_manager' => array(
         'exception_template' => 'error/index',
         'template_map' => array(
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'auth' => __DIR__ . '/../view',
        ),
    ),
);