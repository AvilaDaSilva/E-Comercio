<?php
namespace Auth;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
      
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    $service = $serviceManager->get('doctrine.authenticationservice.orm_default');
              
                   
                    return $service;
                }
            )
        );
    }
    
    /**
     * Executada no bootstrap do módulo
     *
     * @param MvcEvent $e
     */
    public function onBootstrap($e) {

        $app = $e->getParam('application');
        if (method_exists($app->getRequest(), 'getScriptName')) {
            if (!$app->getRequest()->getScriptName() == './vendor/doctrine/doctrine-module/bin/doctrine-module') {
                $app->getRequest()->setBaseUrl(BASE_URL);
                $app->getRequest()->setBasePath(BASE_URL);
            }
        } else {
            $app->getRequest()->setBaseUrl(BASE_URL);
            $app->getRequest()->setBasePath(BASE_URL);
        }

        $session = $e->getApplication()->getServiceManager()->get('Session');
        $session->offsetSet('desenvolvimento', 1);
        
        
        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');

        /** @var \Zend\EventManager\SharedEventManager $sharedEvents */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        date_default_timezone_set('America/Sao_Paulo');

        //adiciona eventos ao módulo
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
    }
   
    /**
     * Verifica se precisa fazer a autorização do acesso
     * @param MvcEvent $event Evento
     * @return boolean
     */
    public function mvcPreDispatch($event) {
        $di = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        $moduleName = $routeMatch->getParam('module');
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');
        $session = $di->get('Session');
        $authService = $di->get('Auth\Service\Auth');
        
        return true;
    }
}