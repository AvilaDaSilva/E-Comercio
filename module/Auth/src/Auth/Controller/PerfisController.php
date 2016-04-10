<?php

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Model\Perfil;
use Auth\Form\Perfil as PerfilForm;

/**
 * @package Controller
 * @autor:Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 **/

class PerfisController extends ActionController {
   
    public function indexAction() {
        $session=$this->getService('Session');
        $json = $this->getService('Auth\Service\Perfil')->getPerfis();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
			'json' => $json,
		));
    }


     public function saveAction() {
        $filial = $this->getService('Auth\Service\Filial')->getFiliais();
        $form = new PerfilForm($filial);
        $perfil = new Perfil();
        $session = $this->getService('Session');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost(); 
            $form->setInputFilter($perfil->getInputFilter()); 
            $form->setData($data); 
            if ($form->isValid()) {
                $service = $this->getService('Auth\Service\Perfil');     
                try {
                    $service->savePerfil($data);
                    $this->flashMessenger()->addSuccessMessage('Perfil armazenado com sucesso');
                } catch (\Exception $e)  
                
                {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/perfis/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);  
        if ($id > 0) { 
            $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $id);
            $form->get('id')->setValue($perfil->id); 
            $form->get('desc_perfil')->setValue($perfil->desc_perfil);
            $filial = $this->getObjectManager()->find('Auth\Model\Filial', $perfil->id_filial);
            $form->get('id_filial')->setValue($filial->id);
        }
        return new ViewModel(
                array('form' => $form,
        ));
    }
    

 public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0); 
        try {
            $this->getService('Auth\Service\Perfil')->deletePerfil($id);
            $this->flashMessenger()->addSuccessMessage('Perfil excluído com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/perfis/index');

    }

}
?>

