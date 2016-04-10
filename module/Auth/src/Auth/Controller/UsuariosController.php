<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\Usuario as FormUsuario;
use Auth\Model\Usuario;

/**
 * Controlador Usuario 
 * @data 29-04-2013
 * @category Auth 
 * @package Controller 
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 * 
 * 
 */
Class UsuariosController extends ActionController {

    public function indexAction() {
        $session = $this->getService('Session');
        if ($this->getRole() == 'USUARIO') {
            $user_id = $this->getUser()->id;
            return $this->redirect()->toUrl(BASE_URL . "/auth/usuarios/save/id/$user_id");
        }
        $json = $this->getService('Auth\Service\Usuario')->getUsuarios($session->offsetGet('buscar'));
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
            'json' => $json
        ));
    }

    public function saveAction() {
        $dataUserPerfil = '';
        $perfis = $this->getService('Auth\Service\Perfil')->getPerfisPEmpresa();
        $form = new FormUsuario($perfis);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $usuario = new Usuario();
            $form->setInputFilter($usuario->getInputFilter());
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $service = $this->getService('Auth\Service\Usuario');
                try {
                    $service->saveUsuario($data);
                    $this->flashMessenger()->addSuccessMessage('Usuário armazenado com sucesso!');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        $role = $this->getRole();
        if ($id != $this->getUser()->id && $role == 'USUARIO') {
            $this->flashMessenger()->addInfoMessage("Você não tem permissão para fazer isto.");
            return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/save/id/' . $this->getUser()->id);
        }
        if ($id > 0) {
            $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $id);
            if (!$usuario) {
                $this->flashMessenger()->addErrorMessage("Usuário não encontrado!");
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
            }
            $form->get('nome')->setValue($usuario->nome);
            $form->get('email')->setValue($usuario->email);
            $form->get('ativo')->setValue($usuario->ativo);
            $form->get('id')->setValue($usuario->id);
            $form->get('login')->setValue($usuario->login);
            $form->get('senha')->setValue('');
            $usuarioPerfil = $this->getService('Auth\Service\UsuarioPerfil')->getPerfisPUsuario($id);
            $dataUserPerfil['perfis'] = $this->getService('Auth\Service\Usuario')->comboFormat($usuarioPerfil, 'id_perfil', 'id_perfil');
            $form->populateValues($dataUserPerfil);
        }
        return new ViewModel(
            array('form' => $form,
                  'checked' => $dataUserPerfil,
                  'role' => $role
            )
        );
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\Usuario');
        try {
            $service->deleteUsuario($id);
            $this->flashMessenger()->addSuccessMessage('Usuário excluído com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
    }

}

?>