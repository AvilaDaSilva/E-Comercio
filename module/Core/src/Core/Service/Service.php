<?php

namespace Core\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Core\Db\TableGateway;

abstract class Service implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        //return $this;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Retrieve TableGateway
     *
     * @param  string $table
     * @return TableGateway
     */
    protected function getTable($table) {
        $sm = $this->getServiceManager();
        $dbAdapter = $sm->get('DbAdapter');
        $tableGateway = new TableGateway($dbAdapter, $table, new $table);
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
     * Retrieve Service
     *
     * @return Service
     */
    protected function getService($service) {
        return $this->getServiceManager()->get($service);
    }

    /**
     *
     * @param type $data
     * @param type $indexValue
     * @param type $indexDescription
     * @return array Array contendo $indexDescription
     */
    public function comboFormat($data, $indexValue, $indexDescription) {
        $combo = array();
        foreach ($data as $d) {
            $combo[$d[$indexValue]] = $d[$indexDescription];
        }
        return $combo;
    }

    /**
     *
     * @return string
     */
    protected function getRole() {
        $role = $this->getService('Session')->offsetGet('role');
        return $role;
    }

    /**
     * autor: Felippe S. Ribeiro dos Santos
     * @param type $value
     * @return \DateTime
     * função para formatar as datas que vem com máscara.
     * ex: se $value = 01/12/2015 retorna objeto datetime 2015-12-01 (Y-m-d);
     */
    public function formatarDatas($value) {
        if (isset($value)) {
            $date = date_create_from_format('d/m/Y', $value);
            $data_aux = date_format($date, 'm/d/Y');
            return new \DateTime($data_aux);
        } else {
            return false;
        }
    }

    /**
     *
     * @param type $value
     * @return $value
     * função que formata os dados de valores monetários para inserir no banco.
     */
    public function formatarValores($value) {
        return str_replace(',', '.', str_replace('.', '', $value));
    }
    /**
     * 
     * @param type $mes (integer)
     * @return string
     * função que formata os meses de inteiro para string
     */
    public function mesExtenso($mes) {
        if ($mes == '1') {
            return 'janeiro';
        }
        if ($mes == '2') {
            return 'fevereiro';
        }
        if ($mes == '3') {
            return 'março';
        }
        if ($mes == '4') {
            return 'abril';
        }
        if ($mes == '5') {
            return 'maio';
        }
        if ($mes == '6') {
            return 'junho';
        }
        if ($mes == '7') {
            return 'julho';
        }
        if ($mes == '8') {
            return 'agosto';
        }
        if ($mes == '9') {
            return 'setembro';
        }
        if ($mes == '10') {
            return 'outubro';
        }
        if ($mes == '11') {
            return 'novembro';
        }
        if ($mes == '12') {
            return 'dezembro';
        }
        return "";
    }
}
