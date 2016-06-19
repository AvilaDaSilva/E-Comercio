<?php

namespace Produtos\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Produtos\Model\Produto;
use Produtos\Form\ProdutoForm as Form;

/**
 * Controlador Usuario 
 * @data 29-04-2013
 * @category Auth 
 * @package Controller 
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 * 
 * 
 */
Class ProdutosController extends ActionController {

    public function indexAction() {
        
        $produtos = $this->getObjectManager()
                ->getRepository('Produtos\Model\Produto')->findAll();
        
        return new ViewModel(['produtos' => $produtos]);
    }
    
    public function saveAction() {
        
        $form = new Form($this->getObjectManager());
        $produto = new Produto();
        $form->bind($produto);
        $id = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $dados = $request->getPost();
            $imagem = $request->getFiles();
            $dados['imagem'] = $imagem['imagem'];
            $form->setData($dados);
            
            if ($form->isValid()) {
                
                if ($dados['id'] == 0 ||
                    $dados['id'] == null ||
                    $dados['id'] == '') {
                    
                    $this->getService('Produtos\Service\Produto')
                        ->saveProduto($dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Produto cadastrada com sucesso!');
                } else {
                    $produto_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Produto')
                        ->findOneBy(['id' => $dados['id']]);
                    $this->getService('Produtos\Service\Produto')
                            ->updateProduto($produto_v->getId(), $dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Produto atualizada com sucesso!');
                }
                
                return $this->redirect()->toUrl(BASE_URL . '/produtos/produtos/index');
            }
            
            $this->flashMessenger()->addErrorMessage('Erro ao cadastrar produto!');
        }
        
        if ($id != 0) {
            $produto_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Produto')
                        ->findOneBy(['id' => $id]);
            $form->bind($produto_v);
        }
            
        return new ViewModel(['form' => $form]);
    }
    
    public function deleteAction() {
        
        $id = $this->params()->fromRoute('id', 0);
        
        if ($id != 0)
            $this->getService('Produtos\Service\Produto')
                ->deleteProduto($id);
            
        return $this->redirect()->toUrl(BASE_URL . '/produtos/produtos/index');
    }
}

?>