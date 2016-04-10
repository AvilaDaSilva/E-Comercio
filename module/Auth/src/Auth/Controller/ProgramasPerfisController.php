<?php

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\ProgramaPerfil as ProgramaPerfilForm;
use Auth\Model\ProgramaPerfil as ProgramaPerfil;

class ProgramasPerfisController extends ActionController {

    /**
     * Index para iniciar os ProgramasPerfis
     */
    public function indexAction() {
        $session = $this->getService('Session');
		$json = $this->getService('Auth\Service\ProgramaPerfil')->getProgramaPerfis();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
        	'json' => $json,
		));
	}

    /*
     * Salva todos os ProgramasPerfis
     */
    public function saveAction() {
        $filial = $this->getService('Auth\Service\Filial')->getFiliais();
        $perfis = $this->getService('Auth\Service\Perfil')->getPerfis();
        $form = new ProgramaPerfilForm($filial, $perfis);
        $programaPerfil = new ProgramaPerfil();
        $session = $this->getService('Session');
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setInputFilter($programaPerfil->getInputFilter());
            $form->setData($request->getPost());
            if($form->isValid()){
                $data = $form->getData();
                $service = $this->getService('Auth\Service\ProgramaPerfil');
                try {
                    $service->saveProgramaPerfil($data);
                    $this->flashMessenger()->addSuccessMessage('Vinculado com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                    echo "$e";
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/programasperfis/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id_programa', 0);

        if($id > 0){
            $programaPerfil = $this->getService('Auth\Service\ProgramaPerfil')->getProgramasPPerfil($id);
            $dataProgramaPerfil['id_programa'] = $id;
            $dataProgramaPerfil['id_perfil'] = $service->comboFormat($programaPerfil, 'id_perfil');
            $form->populateValues($dataProgramaPerfil); 
        }
        return new ViewModel(array(
            'form' => $form
            ));
    }
    /*
     * Deleta todos os ProgramasPerfis
     */
    public function deleteAction() {
        $id =  $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\ProgramaPerfil');
        try {
            $service->deleteProgramaPerfil($id);
            $this->flashMessenger()->addSuccessMessage('Registro excluído com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/programasperfis/index');
    }
    
    public function programasPerfilAction() {
        $this->layout('layout/ajax-layout');
        $id = $this->params()->fromRoute('id', 0);
        $dadosProgramas = array();
        if ($id != 0) 
            $dadosProgramas = $this->getService('Auth\Service\Acao')->getProgramasPFilial($id);
        echo json_encode($dadosProgramas);exit;
    }
    
    public function perfilAjaxAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $aux = $request->getPost();
            $values = get_object_vars($aux);
            $select = $this->getObjectManager()->createQueryBuilder()
                    ->select('Perfil.id')
                    ->from('Auth\Model\ProgramaPerfil', 'PP')
                    ->join('PP.id_perfil', 'Perfil')
                    ->where('PP.id_programa = ?1')
                    ->setParameter(1 , $values['programa']);
            $query = $select->getQuery();
            $response = $query->getResult();
            $result = json_encode($response);
            echo $result;exit;
            
        }
    }
}
?>