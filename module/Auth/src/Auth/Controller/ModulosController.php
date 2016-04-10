<?php

/*
 * Modulos controller
 * @author Ricardo Farina <ricardofarina@unochapeco.edu.br>
 * @author Eduardo Capoani <eduardocapoani@unochapeco.edu.br>
 * 
 */

namespace Auth\Controller;

use Auth\Model\Modulo;
use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\Modulo as ModuloForm;

class ModulosController extends ActionController {

    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\Modulo')->getModulos();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
            'json' => $json,
            ));
    } 
    /**
     * Cria e edita um módulo
     * @return void
     */
    public function saveAction() {
        $form = new ModuloForm($this->getObjectManager());
        $modulo = new Modulo();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($modulo->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $service = $this->getService('Auth\Service\Modulo');
                try {
                    $service->saveModulo($data);
                    $this->flashMessenger()->addSuccessMessage('Módulo armazenado com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }

                return $this->redirect()->toUrl(BASE_URL . '/auth/modulos/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);    
        if ($id > 0) {          
            $modulo = $this->getObjectManager()->find('\Auth\Model\Modulo', $id);   
            $form->get('desc_modulo')->setValue($modulo->desc_modulo);
            $form->get('id')->setValue($modulo->id); 
            $form->get('url_modulo')->setValue($modulo->url_modulo);
        }
        return new ViewModel(//Retorna o Formulário com os dados do modulo
            array('form' => $form,
                )
            );
    }
    /**
     * Acao deletar
     * @return void
     */
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\Modulo');
        try{
            $service ->deleteModulo($id);
            $this->flashMessenger()->addSuccessMessage('Módulo excluído com sucesso');
        }catch(Exception $e){
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/modulos/index');
    }
}

?>