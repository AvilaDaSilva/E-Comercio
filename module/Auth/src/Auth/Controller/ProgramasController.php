<?php

/*
 * ProgramasController.php
 * @author willliam Gustavo Mendo <>
 * 
 */

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\Programa as ProgramaForm;
use Auth\Model\Programa as Programa;

class ProgramasController extends ActionController {

    /**
     * Index Controller com busca e paginação
     * @return void
     */
    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\Programa')->getProgramas();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
            'json' => $json,
                ));
    }

    /**
     * Cria e edita um programa
     * @return void
     */
    public function saveAction() {
        $filial = $this->getService('Auth\Service\Filial')->getFiliais();
        $modulo = $this->getService('Auth\Service\Modulo')->getModulos();
        $form = new ProgramaForm($filial, $modulo);
        $programa = new Programa();
        $session = $this->getService('Session');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($programa->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->getService('Auth\Service\Programa')->savePrograma($data);
                    $this->flashMessenger()->addSuccessMessage('Programa armazenado com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/programas/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $id);
            $form->get('desc_programa')->setValue($programa->desc_programa);     
            $form->get('id_filial')->setValue($programa->id_filial->id);
            $form->get('id_modulo')->setValue($programa->id_modulo->id);
            $form->get('id')->setValue($programa->id);
            $form->get('controller_programa')->setValue($programa->controller_programa);            
        }
        return new ViewModel(array(
                    'form' => $form,
                ));
    }

    /*
     * @Função excluir
     * 
     */
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        try {
            $this->getService('Auth\Service\Programa')->deletePrograma($id);
            $this->flashMessenger()->addSuccessMessage('Programa excluído com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/programas/index');
    }

}

?>
  