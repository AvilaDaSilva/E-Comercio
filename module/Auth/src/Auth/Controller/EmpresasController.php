<?php

/*
 * Controller Empresa
 * @author Rodrigo Angelo Valentini <rodrigoangelo@unochapeco.edu.br>
 * @author Huilson José Lorenzi <huilson@unochapeco.edu.br>
 * 
 */

namespace Auth\Controller;

use Auth\Model\Empresa;
use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\Empresa as EmpresaForm;

class EmpresasController extends ActionController {

    /**
     * Index Controller com busca e paginação
     * @return void
     */
    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\Empresa')->getEmpresas();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
            'json' => $json,
        ));
    }

    /**
     * Cria e edita uma empresa
     * @return void
     */
    public function saveAction() {
        $form = new EmpresaForm;
        $empresa = new Empresa();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($empresa->getInputFilter()); // pego os filtros que estão configurados no modelo "empresa" e adiciono ao form
            $form->setData($data); //seta dados chegando por post no formulário            
            if ($form->isValid()) {
               $service = $this->getService('Auth\Service\Empresa');
                try {
                    $service->saveEmpresa($data);
                    $this->flashMessenger()->addSuccessMessage('Empresa armazenada com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/empresas/index');
            }
        }

        $id = (int) $this->params()->fromRoute('id', 0);    //Pega parametro Id chegando por Get
        if ($id > 0) { //Se id existe será maior que zero
            $empresa = $this->getObjectManager()->find('\Auth\Model\Empresa', $id);            
            $form->get('nome')->setValue($empresa->nome_empresa);   //Preenche o Formulário com os dados daquele empresa           
            $form->get('id')->setValue($empresa->id);
        }
        return new ViewModel(//Retorna o Formulário com os dados do empresa
                array('form' => $form,
        ));
    }

    /**
     * Acao deletar
     * @return void
     */
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\Empresa');
        try {
            $service->deleteEmpresa($id);
            $this->flashMessenger()->addSuccessMessage('Empresa excluída com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toUrl(BASE_URL . '/auth/empresas/index');
    }

}

?>
