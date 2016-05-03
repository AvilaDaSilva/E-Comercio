<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
//use Auth\Form\RecuperaSenha as RecuperaSenha;

class AuthController extends ActionController {

    public function loginAction() {
        $form = new LoginForm();
        $session = $this->getService('Session');
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $adapter = $authService->getAdapter();
            $senha = md5($data['senha']);
            $adapter->setIdentityValue($data['email']);
            $cookie = $data['checkbox'];
            $adapter->setCredentialValue($senha);
            $authResult = $authService->authenticate();
            
            if ($authResult->isValid()) {
                $identity = $authResult->getIdentity();
                $authService->getStorage()->write($identity);
                $session->offSetSet('user', $identity);
                
                if ($identity->getPerfil() == 1)
                    $session->offsetSet('role', 'admin');
                else
                    $session->offsetSet('role', 'cliente');
                
                if ($cookie == 1) {
                    setcookie('email', $data['email'], time()+60*60*24*365, '/');
                    setcookie('senha', md5($data['senha']), time()+60*60*24*365, '/');
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
            } else {
                $this->flashMessenger()->addErrorMessage('Usuário ou senha incorretos');
                $session->offsetSet('erro', 'Usuario ou senha incorreto');
            }
        } else {
            
            if ($session->offsetGet('user'))
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
        }
        
        return new ViewModel(
                array('form' => $form)
        );
    }

    public function logoutAction() {
        $this->getService('Auth\Service\Auth')->logout();
        return $this->redirect()->toUrl(BASE_URL . '/');
    }

    public function recuperarSenhaAction() {
        
    }

}
