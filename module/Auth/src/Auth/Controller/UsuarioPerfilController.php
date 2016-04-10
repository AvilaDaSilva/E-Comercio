<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\UsuarioPerfil as FormUsuarioPerfil;
use Auth\Model\UsuarioPerfil as UsuarioPerfil;

/**
 * Controlador Ação
 * @data 29-04-2013
 * @category Auth
 * @package Controller 
 * @author  Orestes Paulo Di Domenico <orestes.d@unochapeco.edu.br>
 * @author  Jean Cesar Detoni <jean_cd@unochapeco.edu.br>
 */
class UsuarioPerfilController extends ActionController {

    /**
     * Index Controller com busca e paginação
     * @return void
     */
    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\UsuarioPerfil')->getUsuariosPerfis();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
        		'json' => $json,
        ));
    }

    public function saveAction() {
        $service = $this->getService('Auth\Service\UsuarioPerfil');
        $usuarios = $this->getService('Auth\Service\Usuario')->getUsuarios();
        $perfis = $this->getService('Auth\Service\Perfil')->getPerfis();
        $form = new FormUsuarioPerfil($service->comboFormat($usuarios, 'id', 'nome'), $service->comboFormat($perfis, 'id', 'desc_perfil'));
        $usuarioPerfil = new UsuarioPerfil();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($usuarioPerfil->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                try {
                    $service->saveUsuarioPerfil($data);
                    $this->flashMessenger()->addSuccessMessage('Vinculado com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarioperfil');
            }
        }
        $id = (int) $this->params()->fromRoute('id_usuario', 0);
        if ($id > 0) {
            $usuarioPerfil = $service->getPerfisPUsuario($id);
            $dataUserPerfil['id_usuario'] = (int)$id;
            $dataUserPerfil['id_perfil'] = $service->comboFormat($usuarioPerfil, 'id_perfil');
            $form->populateValues($dataUserPerfil);
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        try {
            $this->getService('Auth\Service\UsuarioPerfil')->deleteUsuarioPerfil($id);
            $this->flashMessenger()->addSuccessMessage('Registro excluído com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toUrl(BASE_URL . '/auth/usuarioperfil/index');
    }
    //Função para setar usuario e retornar os perfis vinculados

    public function setAjaxAction() {
        $request = $this->getRequest();
        if($request->isPost()) {
            $aux = $request->getPost();
            $values = get_object_vars($aux);
            $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Perfil.id')
                ->from('Auth\Model\UsuarioPerfil', 'UP')
                ->join('UP.id_perfil', 'Perfil')
                ->where('UP.id_usuario = ?1')
                ->setParameter(1, $values['usuario']);
            $query = $select->getQuery();
            $response = $query->getResult();
            $result = json_encode($response);
            echo $result;exit;
        }
    }

}

?>