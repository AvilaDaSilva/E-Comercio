<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\UsuarioForm;
use Auth\Model\Usuario;
use Auth\Validator\UsuarioValidator;
use Auth\Validator\PessoaValidator;
use Auth\Validator\EnderecoValidator;
use Zend\InputFilter\InputFilter;

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
        
        $pessoas = $this->getObjectManager()->getRepository('Auth\Model\Pessoa')
            ->findAll();
        
        return new ViewModel(['pessoas' => $pessoas]);
    }


    public function saveAction() {
        
        $form = new UsuarioForm();
        $usuario = new Usuario();
        $form->bind($usuario);
        $id = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $dados = $request->getPost();
            $form->setData($dados);
            
            if ($form->isValid()) {
                
                if ($dados['usuario']['id'] == 0 ||
                    $dados['usuario']['id'] == null ||
                    $dados['usuario']['id'] == '') {
                    
                    $this->getService('Auth\Service\Pessoa')->savePessoa($dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Usuário cadastrado com sucesso!');
                } else {
                    $pessoa_v = $this->getObjectManager()
                        ->getRepository('Auth\Model\Pessoa')
                        ->findOneBy(['id' => $id]);
                    $this->getService('Auth\Service\Pessoa')
                            ->updatePessoa($pessoa_v->getId(), $dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Usuário cadastrado com sucesso!');
                }
                
                return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
            }
            
            $this->flashMessenger()->addErrorMessage('Erro ao cadastrar usuário!');
        }
        
        if ($id != 0) {
            $pessoa_v = $this->getObjectManager()->getRepository('Auth\Model\Pessoa')
                ->findOneBy(['id' => $id]);
            $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
                ->findOneBy(['pessoa' => $pessoa_v]);
            $usuario->setSenha('');
            $form->bind($usuario);
        }
            
        return new ViewModel(['form' => $form]);
    }
    

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $this->getService('Auth\Service\Pessoa')->deletePessoa($id);
        
        return $this->redirect()->toUrl(BASE_URL . '/auth/usuarios/index');
    }

}

?>