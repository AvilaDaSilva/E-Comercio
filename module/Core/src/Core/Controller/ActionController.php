<?php

namespace Core\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Core\Db\TableGateway;

class ActionController extends AbstractActionController {
    
     protected $user = null;

    /**
     * Returns a TableGateway
     *
     * @param  string $table
     * @return TableGateway
     */
    protected function getTable($table) {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('DbAdapter');
        $schema = new $table;
        $schema = $schema->schemaName;


        $tableGateway = new TableGateway($dbAdapter, $table, new $table, $schema);
        $tableGateway->initialize();

        return $tableGateway;
    }

    /**
     * Retrieve EntityManager
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getObjectManager() {
        $objectManager = $this->getService('Doctrine\ORM\EntityManager');
        return $objectManager;
    }
    
    /**
     * Returns a Service
     *
     * @param  string $service
     * @return Service
     */
    protected function getService($service) {
        return $this->getServiceLocator()->get($service);
    }
    
   /**
    * 
    * @return Obj Objeto contendo os dados do usuário autenticado
    */
    
    protected function getUser(){
        $this->user = $this->getService('Session')->offsetGet('user');
        return $this->user;
    }

    /**
    * 
    * @return string
    */
    
    protected function getRole(){
        $role = $this->getService('Session')->offsetGet('role');
        return $role;
    }

}