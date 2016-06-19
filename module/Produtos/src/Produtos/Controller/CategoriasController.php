<?php

namespace Produtos\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\InputFilter\InputFilter;
use Produtos\Form\CategoriaForm as Form;
use Produtos\Model\Categoria;


/**
 * Controlador Usuario 
 * @data 29-04-2013
 * @category Auth 
 * @package Controller 
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 * 
 * 
 */
Class CategoriasController extends ActionController {

    public function indexAction() {
        
        $categorias = $this->getObjectManager()
                ->getRepository('Produtos\Model\Categoria')->findAll();
        
        return new ViewModel(['categorias' => $categorias]);
    }
    
    public function saveAction() {
        
        $form = new Form();
        $categoria = new Categoria();
        $form->bind($categoria);
        $id = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $dados = $request->getPost();
            $form->setData($dados);
            
            if ($form->isValid()) {
                
                if ($dados['id'] == 0 ||
                    $dados['id'] == null ||
                    $dados['id'] == '') {
                    
                    $this->getService('Produtos\Service\Categoria')
                        ->saveCategoria($dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Categoria cadastrada com sucesso!');
                } else {
                    $categoria_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Categoria')
                        ->findOneBy(['id' => $dados['id']]);
                    $this->getService('Produtos\Service\Categoria')
                            ->updateCategoria($categoria_v->getId(), $dados);
                    $this->flashMessenger()
                        ->addSuccessMessage('Categoria atualizada com sucesso!');
                }
                
                return $this->redirect()->toUrl(BASE_URL . '/produtos/categorias/index');
            }
            
            $this->flashMessenger()->addErrorMessage('Erro ao cadastrar categoria!');
        }
        
        if ($id != 0) {
            $categoria_v = $this->getObjectManager()
                        ->getRepository('Produtos\Model\Categoria')
                        ->findOneBy(['id' => $id]);
            $form->bind($categoria_v);
        }
            
        return new ViewModel(['form' => $form]);
    }
    
    public function deleteAction() {
        
        $id = $this->params()->fromRoute('id', 0);
        
        if ($id != 0)
            $this->getService('Produtos\Service\Categoria')
                            ->deleteCategoria($id);
            
        return $this->redirect()->toUrl(BASE_URL . '/produtos/categorias/index');
    }
}

?>