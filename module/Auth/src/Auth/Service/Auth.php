<?php

namespace Auth\Service;

use Core\Service\Service;
use Zend\Authentication\AuthenticationService;
use Core\Model\EntityException as EntityException;
use Core\Service\Email as Email;

class Auth extends Service {


    /**
     * Construtor da classe
     * @return void 
     */
    public function authenticate($params, $WS = null) {
        
        if (!isset($params['login']) || !isset($params['senha']) || (!$params['login']) || (!$params['senha'])) {
            throw new \Exception("Parâmetros inválidos");
        }
        $senha = md5($params['senha']);
        $auth = new AuthenticationService();
        $authAdapter = $this->authAdapter = $this->getServiceManager()
                ->get('doctrine.authenticationadapter.ormdefault');
        $authAdapter->setTableName('usuario')
                ->setIdentityColumn('email')
                ->setCredentialColumn('senha')
                ->setIdentity($params['email'])
                ->setCredential($senha);
        $result = $auth->authenticate($authAdapter);
        
        if (!$result->isValid()) {
            throw new \Exception("Login ou senha inválidos");
        }
//
        //salva o usuário na sessão

        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $authAdapter->getResultRowObject());

        return true;
    }

    /**
     * Faz a autorização do usuário para acessar o recurso
     * @param string $moduleName Nome do módulo sendo acessado
     * @param string $controllerName Nome do controller
     * @param string $actionName Nome da ação
     * @return boolean
     */
    public function authorize($moduleName, $controllerName, $actionName) {
        $auth = new AuthenticationService();
        $role = 'gest';
        if ($auth->hasIdentity()) {
            $session = $this->getServiceManager()->get('Session');
            if (!$session->offsetGet('role'))
                $role = 'gest';
            else
                $role = $session->offsetGet('role');
        }
        
        $resource = $controllerName . '.' . $actionName;
        $acl = $this->getServiceManager()->get('Core\Acl\Builder')->build();
        if ($acl->isAllowed($role, $resource)) {
            return true;
        }
        
        return false;
    }

    public function logout() {
        $Auth = new AuthenticationService();
        $session = $this->getServiceManager()->get('Session');
        $session->offsetUnset('user');
        $session->offsetUnset('role');
        setcookie('email', null, 0, '/');
        setcookie('senha', null, 0, '/');
        $Auth->clearIdentity();
        return true;
    }

    public function clearIdentity() {
        $this->getStorage()->clear();
    }

}
