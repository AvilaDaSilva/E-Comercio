<?php

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Model\Filial;
use Auth\Form\Filial as FilialForm;

class FiliaisController extends ActionController {

    public function indexAction() {
        $session = $this->getService('Session');
        $json = $this->getService('Auth\Service\Filial')->getFiliais();
        $jsonObject = json_encode($json);
        $session->offsetSet('json', $jsonObject);
        return new ViewModel(array(
            'json' => $json,
        ));
    }
    /* =========================================================================
     * @Função Criar Perfilndex/save
     */

    public function saveAction() {
        $empresa = $this->getService('Auth\Service\Empresa')->getEmpresas();
        $form = new FilialForm($empresa);
        $filial = new Filial();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setInputFilter($filial->getInputFilter());
            $form->setData($data);

            //======================================================
            // Função para fazer upload de imagens.
            $target_path = BASE_PROJECT . '/public/images/baners/'; // COLOCAR define('BASE_PROJECT', '/var/www/sis/trunk');
            $imagem = basename($_FILES['url_img_filial']['name']);
            $target_path = $target_path . basename($_FILES['url_img_filial']['name']);
            $validator = new \Zend\Validator\File\IsImage(array('image/jpg', 'image/png', 'image/jpeg'));
            move_uploaded_file($_FILES['url_img_filial']['tmp_name'], $target_path);
            if ($validator->isValid($target_path)) {
                $rand = uniqid();
                $origem = $target_path;
                $novo = BASE_PROJECT . '/public/images/baners/' . $rand;
                copy($origem, $novo);
                unlink($origem);
            } else {
                $this->flashMessenger()->addInfoMessage('Erro no upload da imagem');
                return $this->redirect()->toUrl(BASE_URL . '/auth/filiais/index');
            }
            if ($form->isValid()) {
                $service = $this->getService('Auth\Service\Filial');
                try {
                    $service->saveFiliais($data, $rand);
                    $this->flashMessenger()->addSuccessMessage('Filial armazenada com sucesso');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/filiais/index');
            }
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $filial = $this->getObjectManager()->find('\Auth\Model\filial', $id);
            $form->get('nome_filial')->setValue($filial->nome_filial);
            $form->get('desc_modulo_filial')->setValue($filial->desc_modulo_filial);
            $form->get('desc_exibi_filial')->setValue($filial->desc_exibi_filial);
            $form->get('id_empresa')->setValue($filial->id_empresa->id);
            $form->get('id')->setValue($filial->id);
        }
        return new ViewModel(array('form' => $form,));
    }

    /* ==========================================================================
     * @Função excluir
     *
     */

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        $service = $this->getService('Auth\Service\Filial');
        try {
            $service->deleteFilial($id);
            $this->flashMessenger()->addSuccessMessage('Filial excluída com sucesso');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/filiais/index');
    }

}

?>
