<?php

namespace Produtos\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Produtos\Form\EstoqueForm as Form;
use Produtos\Model\Estoque;


/**
 * Controlador Usuario 
 * @data 29-04-2013
 * @category Auth 
 * @package Controller 
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 * 
 * 
 */
Class EstoquesController extends ActionController {
    
    public function saveAction() {
        
        $form = new Form();
        $estoque = new Estoque();
        $form->bind($estoque);
        $id = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $dados = $request->getPost();
            $form->setData($dados);
            
            
            if ($form->isValid()) {
                $estoque_v = $this->getObjectManager()
                    ->getRepository('Produtos\Model\Estoque')
                    ->findOneBy(['id' => $dados['id']]);
                $this->getService('Produtos\Service\Estoque')
                    ->updateEstoque($estoque_v->getId(), $dados);
                
                $this->flashMessenger()
                        ->addSuccessMessage('Estoque atualizado com sucesso!');
                
                return $this->redirect()->toUrl(BASE_URL . '/produtos/produtos/index');
            }
            
            $this->flashMessenger()->addErrorMessage('Erro ao atualizar estoque!');
        }
        
        if ($id != 0) {
            $estoque_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Estoque')
                        ->findOneBy(['produto' => $id]);
            $form->bind($estoque_v);
        }
            
        return new ViewModel(['form' => $form]);
    }
}

?>