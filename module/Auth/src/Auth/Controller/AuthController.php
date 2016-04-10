<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\Auth as LoginForm;
use Auth\Form\RecuperaSenha as RecuperaSenha;
use Auth\Form\NovaSenha as NovaSenha;

class AuthController extends ActionController {

    public function indexAction() {
        $session = $this->getService('session');
        if (isset($_COOKIE['usuario']) && $_COOKIE['usuario'] != null && isset($_COOKIE['senha']) && $_COOKIE['senha'] != null) {
            $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $adapter = $authService->getAdapter();
            $senha = $_COOKIE['senha'];
            $adapter->setIdentityValue($_COOKIE['usuario']);
            $adapter->setCredentialValue($senha);
            $authResult = $authService->authenticate();
            if ($authResult->isValid()) {
                $identity = $authResult->getIdentity();
                $authService->getStorage()->write($identity);
                $session->offSetSet('user', $identity);
                $session->offsetSet('role', 'Authenticated');
                return $this->redirect()->toUrl(BASE_URL . '/auth/selecionar-perfil/index');
            } else {
                $this->flashMessenger()->addErrorMessage('Usuário ou senha incorretos');
                setcookie('usuario');
                setcookie('senha');
                $session->offsetSet('erro', 'Usuario ou senha incorreto');
                return $this->redirect()->toUrl(BASE_URL . '/auth/auth');
            }
        } else if (isset($session['user']) && $session['role'] !== 'Unauthenticated') {
            return $this->redirect()->toUrl(BASE_URL . '/auth/selecionar-perfil/index');
        }
        $this->layout('layout/loginFavero');
        $form = new LoginForm();
        return new ViewModel(
                array('form' => $form)
        );
    }

    public function loginAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new \Exception('Acesso inválido');
        }
        $session = $this->getService('Session');
        $data = $request->getPost();
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $adapter = $authService->getAdapter();
        $senha = md5($data['senha']);
        $adapter->setIdentityValue($data['login']);
        $cookie = $data['checkbox'];
        $adapter->setCredentialValue($senha);
        $authResult = $authService->authenticate();
        if ($authResult->isValid()) {
            $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('login' => $data['login']));
            if ($usuario->ativo->getId() == 2) {
                $this->flashMessenger()->addInfoMessage('Usuário desativado, entre em contato com o administrador');
                return $this->redirect()->toUrl(BASE_URL . '/auth/auth');
            }
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);
            $session->offSetSet('user', $identity);
            $session->offsetSet('role', 'Authenticated');
            if ($cookie == 1) {
                setcookie('usuario', $data['login'], time()+60*60*24*365, '/');
                setcookie('senha', md5($data['senha']), time()+60*60*24*365, '/');
            }
            return $this->redirect()->toUrl(BASE_URL . '/auth/selecionar-perfil/index');
        } else {
            $this->flashMessenger()->addErrorMessage('Usuário ou senha incorretos');
            $session->offsetSet('erro', 'Usuario ou senha incorreto');
            return $this->redirect()->toUrl(BASE_URL . '/auth/auth');
        }
        try {
            
        } catch (\Exception $e) {
            $session->offsetSet('error', $e->getMessage());
            return $this->redirect()->toUrl(BASE_URL . '/auth/index');
        }
    }

    public function naoAutorizadoAction() {
        $this->flashMessenger()->addErrorMessage("Usuário não possui um programa vinculado a este perfil, entre em contato com o administrador!");
        return $this->redirect()->toUrl(BASE_URL . '/auth/auth/index');
    }

    public function logoutAction() {
        $this->getService('Auth\Service\Auth')->logout();
        return $this->redirect()->toUrl(BASE_URL . '/');
    }

    public function recuperaSenhaAction() {
        $this->layout('layout/loginFavero');
        $form = new RecuperaSenha();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($values);
            if ($form->isValid()) {
                try {
                    $this->getService('Auth\Service\Auth')->recuperaSenha($values);
                    $this->flashMessenger()->addSuccessMessage('Um e-mail com instruções de como recuperar sua senha foi enviado.');
                } catch (\Exception $e) {

                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth');
            }
        }
        return new ViewModel(
                array('form' => $form)
        );
    }

    public function novaSenhaAction() {
        $this->layout('layout/loginFavero');
        $hash = $this->params()->fromRoute('hash', 0);
        if ($hash === 0) {
            $this->flashMessenger()->addErrorMessage('Link inválido');
            return $this->redirect()->toUrl(BASE_URL . '/auth');
        }
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('hash_nova_senha' => $hash));
        if (!$usuario) {
            $this->flashMessenger()->addErrorMessage('Link inválido');
            return $this->redirect()->toUrl(BASE_URL . '/auth');
        }
        if ($usuario->hash_nova_senha != $hash) {
            $this->flashMessenger()->addErrorMessage('Link inválido');
            return $this->redirect()->toUrl(BASE_URL . '/auth');
        }
        $form = new NovaSenha($usuario, $hash);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost();
            $form->setInputFilter($form->getInputFilter());
            $form->setData($values);
            if ($form->isValid()) {
                try {
                    $this->getService('Auth\Service\Auth')->novaSenha($values);
                    $this->flashMessenger()->addSuccessMessage('Senha alterada com sucesso');
                } catch (Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth');
            }
        }
        return new ViewModel(
                array('form' => $form)
        );
    }

}
