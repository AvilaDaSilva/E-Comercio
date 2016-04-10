<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Log
 * 
 * @category Auth
 * @package Service
 * @author Joao Krzyzaniak <joaovicente@unochapeco.edu.br>  
 */
class Log extends Service {

    /**
     * Serviço para checar a autenticação do usuário
     * 
     * @author Joao Krzyzaniak <joaovicente@unochapeco.edu.br>  
     * @param Array $userSession
     * @param Array $params
     * @param Array $arrayKeysParam  
     * @param string $actionName  
     * @param string $controllerName  
     * @param string $moduleName  
     */
    public function getAllLogs($search = null) {
        $dataini = null;
        $datafim = null;
        if(isset($search['start'])){
            $aux = $search['start'];
            $split = explode('/', $aux);
            $a = $split[1].'/'.$split[0].'/'.$split[2];
            $dataini = new \DateTime($a);
            $dataini = $dataini->format('d-m-Y');
        }
        if(isset($search['end'])){
        	$aux = $search['end'];
        	$split = explode('/', $aux);
        	$a = $split[1].'/'.$split[0].'/'.$split[2];
        	$datafim = new \DateTime($a);
        	$datafim = $datafim->format('d-m-Y');
        }        
        
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Log.id', 'Log.data', 'Log.hora','Log.acao', 'Log.controlador', 'Log.modulo', 'Usuario.nome')
                ->from('Auth\Model\Log', 'Log')
                ->join('Log.usuario', 'Usuario')
                ->addOrderBy('Log.data', 'DESC')->where('Log.data >= ?1')
                	->setParameter(1, $dataini)
                ->andWhere('Log.data <= ?2')
                	->setParameter(2, $datafim);     
        $result = $select->getQuery()->getResult();
        return $result;
    }

    public function getUsuariosNome($search = null) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Usuario.id', 'Usuario.nome')
                ->from('\Auth\Model\Usuario', 'Usuario')
                ->orderBy('Usuario.nome', 'ASC')
                ->where("Usuario.nome LIKE ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function checarAutenticacao($userSession, $params, $arrayKeysParams, $actionName, $controllerName, $moduleName) {
     
        $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $userSession->id);
        $paramsAction = '';
        foreach ($arrayKeysParams as $key) {
            if ($key != 'action' && $key != 'module' && $key != 'controller' && $key != '__NAMESPACE__' && $key != '__CONTROLLER__') {
                $paramsAction .= "/$key/" . $params[$key];
            }
        }
          
        $logger = new \Auth\Model\Log();
        $logger->acao = $actionName;
        $logger->controlador = $controllerName;
        $logger->modulo = $moduleName;
        $logger->usuario = $usuario;
        $logger->params = $paramsAction;
        $data = new \DateTime('now');
        $hora = $data->format('H:i:s');
        $logger->data = $data;
        $logger->hora = $hora; 
        $this->getObjectManager()->persist($logger);
        try {
            $this->getObjectManager()->flush();
        } catch (Exception $e) {
            throw new EntityException('Erro na autenticação');
        }
    }

    public function deletarHistoricoAntigo($diminuiDia) {
        $dataHojee = date("Y/m/d");
        $dia = date('d/m/Y', strtotime("-" . $diminuiDia . " days", strtotime($dataHojee)));
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Log.id', 'Log.data_hora')
                ->from('Auth\Model\Log', 'Log')
                ->orderBy('Log.data_hora', 'ASC')
                ->where('Log.data_hora <= ?1')
                ->setParameters(array(1 => $dia)); //$date . '%
        $query = $select->getQuery();
        $result = $query->getResult();
        foreach ($result as $list) {
            $log = $this->getObjectManager()->find('Auth\Model\Log', $list['id']);
            $this->getObjectManager()->remove($log);
        }
        $this->getObjectManager()->flush();
    }

}
