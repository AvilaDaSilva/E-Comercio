<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\Acao as AcaoForm;
use Auth\Model\Acao as Acao;
 
/**
 * Controlador Ação
 * @data 29-04-2013
 * @category Auth
 * @package Controller 
 * @author  Daniel
 */
class AcaoController extends ActionController {

    /**
     * Index Controller com busca e paginação
     * @return void
     */
    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\Acao')->getAcoes();
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
        $filial = $this->getService('Auth\Service\Filial')->getFiliais();
        $form = new AcaoForm($filial);
        $acao = new Acao();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($acao->getInputFilter());
            $form->setData($data); //seta dados chegando por post no formulário
            if ($form->isValid()) {
                $service = $this->getService('Auth\Service\Acao');
                try {
                    $service->saveAcao($data);
                    $this->flashMessenger()->addSuccessMessage('Ação armazenada com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/acao/index');
            }
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $acao = $this->getObjectManager()->find('\Auth\Model\Acao', $id);
            $form->get('id')->setValue($acao->id);
            $form->get('desc_acao')->setValue($acao->desc_acao);
            $form->get('id_filial')->setValue($acao->id_programa->id_filial->id);
            $programas = $this->getObjectManager()->getRepository("\Auth\Model\Programa")->findBy(array('id_filial' => $acao->id_programa->id_filial));
            $programasCombo = array();
            foreach($programas as $programa){
                $programasCombo[$programa->id] = $programa->desc_programa;
            }
            $form->get('id_programa')->setValueOptions($programasCombo)->setEmptyOption('SELECIONE UM PROGRAMA');            
            
            $form->get('id_programa')->setValue($acao->id_programa->id);
            $form->get('acao_url')->setValue($acao->acao_url);
        }

        return new ViewModel(//Retorna o Formulário com os dados do modulo
                array('form' => $form,
                ));
    }
    /**
     * Acao deletar
     * @return void
     */
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\Acao');
        try {
            $service->deleteAcao($id);
            $this->flashMessenger()->addSuccessMessage('Ação excluída com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/acao/index');
    }

    public function programasAcaoAction() {
        $this->layout('layout/ajax-layout');
        $id = $this->params()->fromRoute('id', 0);
        $dadosProgramas = array();
        if ($id != 0) 
            $dadosProgramas = $this->getService('Auth\Service\Acao')->getProgramasPFilial($id);
        return new ViewModel(array(
                'programas' => $dadosProgramas
        ));
    }
    
    
}