<?php

namespace Produtos\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Produtos\Model\Promocao;
use Produtos\Form\PromocaoForm as Form;

/**
 * Controlador Usuario 
 * @data 29-04-2013
 * @category Auth 
 * @package Controller 
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 * 
 * 
 */
Class PromocoesController extends ActionController {

    public function indexAction() {
        
        $promocoes = $this->getObjectManager()
                ->getRepository('Produtos\Model\Promocao')->findAll();
        
        return new ViewModel(['promocoes' => $promocoes]);
    }
    
    public function saveAction() {
        
        $form = new Form($this->getObjectManager());
        $promocao = new Promocao();
        $form->bind($promocao);
        $id = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $dados = $request->getPost();
            $form->setData($dados);
            
            if ($form->isValid()) {
                
                if ($dados['id'] == 0 ||
                    $dados['id'] == null ||
                    $dados['id'] == '') {
                    
                    $this->getService('Produtos\Service\Promocao')
                        ->savePromocao($dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Promocao cadastrada com sucesso!');
                } else {
                    $promocao_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Promocao')
                        ->findOneBy(['id' => $dados['id']]);
                    $this->getService('Produtos\Service\Promocao')
                            ->updatePromocao($promocao_v->getId(), $dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Promocao atualizada com sucesso!');
                }
                
                return $this->redirect()->toUrl(BASE_URL . '/produtos/promocoes/index');
            }
            
            $this->flashMessenger()->addErrorMessage('Erro ao cadastrar promocao!');
        }
        
        if ($id != 0) {
            $promocao_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Promocao')
                        ->findOneBy(['id' => $id]);
            $form->bind($promocao_v);
        }
            
        return new ViewModel(['form' => $form]);
    }
    
    public function deleteAction() {
        
        $id = $this->params()->fromRoute('id', 0);
        
        if ($id != 0)
            $this->getService('Produtos\Service\Promocao')
                ->deletePromocao($id);
            
        return $this->redirect()->toUrl(BASE_URL . '/produtos/promocoes/index');
    }
}

?>