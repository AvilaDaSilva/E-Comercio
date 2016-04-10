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
                ->setIdentityColumn('login')
                ->setCredentialColumn('senha')
                ->setIdentity($params['login'])
                ->setCredential($senha);
        $result = $auth->authenticate($authAdapter);
        if (!$result->isValid()) {
            throw new \Exception("Login ou senha inválidos");
        }

        //salva o usuário na sessão

        $session = $this->getServiceManager()->get('Session');
        $session->offsetSet('user', $authAdapter->getResultRowObject());

//        //salva os perfis na sessão
        $perfis = $this->getPerfilUsuario($params['login']);
        if (count($perfis) < 1)
            throw new \Exception("Usuário não possui nenhum perfil neste sistema! Contate o administrador.");
        $filiais = $this->getFiliais($perfis);


        $session->offsetSet('filiais', $filiais);

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
        $role = 'Unauthenticated';
        if ($auth->hasIdentity()) {
            $session = $this->getServiceManager()->get('Session');
            if (!$session->offsetGet('role'))
                $role = 'Unauthenticated';
            else
                $role = $session->offsetGet('role');
        }
        $role = ucfirst(strtolower($role));
        
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
        $session->offsetUnset('id_filial_select');
        $session->offsetUnset('role');
        $session->offsetUnset('img_filial');
        $session->offsetUnset('programas');
        $session->offsetUnset('perfis');
        $session->offsetUnset('filiais');
        setcookie('usuario', null, 0, '/');
        setcookie('senha', null, 0, '/');
        $Auth->clearIdentity();
        return true;
    }

    public function clearIdentity() {
        $this->getStorage()->clear();
    }
    
    public function recuperaSenha($values){
        $login = $values['login'];
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('login' => $login));
        if(!$usuario)
            throw new EntityException('Não existe um usuario com este e-mail');
        $data = new \Datetime('now');
        $aux = $usuario->nome.$data->format('d/m/Y');
        $hash = md5($aux);
        $usuario->hash_nova_senha = $hash;
        $usuario->senha = md5(uniqid());
        $this->getObjectManager()->persist($usuario);
        $email = new Email();
        $url = $_SERVER['HTTP_HOST'];
        $texto = 'Acesse o link abaixo para alterar sua senha '."\r\n"
                ."http://$url". BASE_URL.'/auth/auth/nova-senha/hash/'.$hash;
        $from = "Sistema Interno";
        $to['email'] = "$usuario->email";
        $to['name'] = "$usuario->nome";
        $titulo = "Nova Senha";
        $email->send($texto, $from, $to, null, $titulo);
        try{
            $this->getObjectManager()->flush();
        } catch (Exception $e) {
            throw new EntityException('Não foi possivel iniciar o processo de alteração de senha');
        }
        
    }
    
    public function novaSenha($values){
        $login = $values['login'];
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\usuario')->findOneBy(array('login' => $login));
        if(!$usuario){
            throw new EntityException('Usuario não encontrado');
        }
        $senha = md5($values['senha']);
        $usuario->senha = $senha;
        $usuario->hash_nova_senha = '';
        $this->getObjectManager()->persist($usuario);
        try{
            $this->getObjectManager()->flush();
        } catch (Exception $e) {
            throw new EntiryException("Não foi possivel alterar a senha");
        }
    }

}
