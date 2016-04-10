<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\Padrao as PadraoForm;

class PadraoController extends ActionController {

    public function saveAction() {
        $session = $this->getService('session');
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('email' => $this->getUser()->email));
        $request = $this->getRequest();
        $prog = $session['programas'];
        $form = new PadraoForm($prog);
        if ($request->isPost()) {
            $values = $request->getPost();
            //var_dump($values); exit;
            $form->setInputFilter($form->getInputFilter());
            $form->setData($values);
            if ($form->isValid()) {
                $programa = $this->getObjectManager()->find('Auth\Model\Programa', $values['programa']);
                $usuario->programa = $programa;
                $this->getObjectManager()->persist($usuario);
                try {
                    $this->getObjectManager()->flush();
                    $this->flashMessenger()->addSuccessMessage('Programa padrão salvo com sucesso');
                } catch (Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Não foi possivel salvar o programa padrão');
                }
                $prog = $this->getObjectmanager()->find('Auth\Model\Programa', $usuario->programa);
            	$modulo = $this->getObjectmanager()->find('Auth\Model\Modulo', $prog->id_modulo);
            	$modulo = strtolower($modulo->url_modulo);
            	$controller = strtolower($prog->controller_programa);
            	return $this->redirect()->toUrl(BASE_URL.'/'.$modulo.'/'.$controller.'/index');
            }
        }
        return new ViewModel(
                array('form' => $form,
                )
        );
    }

}