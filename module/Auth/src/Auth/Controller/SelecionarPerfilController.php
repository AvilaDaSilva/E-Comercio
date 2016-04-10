<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;

Class SelecionarPerfilController extends ActionController {

    public function indexAction() {
        $session = $this->getService('session');
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('email' => $this->getUser()->email));
        $empresa = $this->getService('Auth\Service\Empresa')->getEmpresasPUsuario($usuario->id);
        if (!$empresa) {
            $this->flashMessenger()->addInfoMessage(('Seu usuario não está vinculado a uma empresa, contate o administrador do sistema'));
            return $this->redirect()->toUrl(BASE_URL . '/auth');
        }
        if (count($empresa) == 1) {
            $session->offsetSet('empresa', $empresa);
            return $this->redirect()->toUrl(BASE_URL . '/auth/selecionar-perfil/selecionar-filial/id/' . $empresa[0]['id']);
        }
        return new ViewModel(array(
            'empresa' => $empresa,
        ));
    }

    public function selecionarFilialAction() {
        $session = $this->getService('session');
        $id = $this->params()->fromRoute('id', 0);
        $filial = $this->getService('Auth\Service\Filial')->getFiliaisPEmpresa($id);
        if (!$filial) {
            $this->flashMessenger()->addInfoMessage(('Seu usuario não está vinculado a uma filial, contate o administrador do sistema'));
            return $this->redirect()->toUrl(BASE_URL . '/auth');
        }
        if (count($filial) == 1) {
            $session->offsetSet('filial', $filial);
            return $this->redirect()->toUrl(BASE_URL . '/auth/selecionar-perfil/selecionar-perfil/id/' . $filial[0]['id']);
        }
        return new ViewModel(array(
            'filial' => $filial,
        ));
    }

    public function selecionarProgramaAction() {
        $session = $this->getService('session');
        $id = $this->params()->fromRoute('id', 0);
        $perfil = $this->getObjectManager()->find('Auth\Model\Perfil', $id);
        $session->offsetSet('role', $perfil->desc_perfil);
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('email' => $this->getUser()->email));
        $programas = $this->getService('Auth\Service\Programa')->getProgramasPPerfil($id);
        foreach($programas as $key => $dados) {
            $subNivel = $this->getService('Auth\Service\Programa')->getSubPrograma($dados['id']);
            if($subNivel)
                $programas[$key]['subNivel'] = $subNivel;
        }
//        var_dump($programas);exit;
        if ($programas) {
            $session->offsetSet('programas', $programas);
        } else {
            $this->flashMessenger()->addInfoMessage('Seu usuário não está vinculado a um perfil, solicite ao administrador um vínculo!!!');
            return $this->redirect()->toUrl(BASE_URL . 'auth/auth');
        }
        if ($usuario->programa == null) {
            $noticias = $this->getObjectManager()->find('Auth\Model\Programa', 28);
            $usuario = $this->getObjectManager()->find('Auth\Model\Usuario', $this->getUser()->id);
            $usuario->programa = $noticias;
            $this->getObjectManager()->persist($usuario);
            try {
                $this->getObjectmanager()->flush();
            } catch (\Exception $e) {
                echo $e;exit;
                $this->flashMessenger()->addErrorMessage('Não foi possivel salvar um programa padrão');
                return $this->redirect()->toUrl(BASE_URL . '/auth/noticias');
            }
            return $this->redirect()->toUrl(BASE_URL . '/auth/noticias');
        } else {
            $prog = $this->getObjectmanager()->find('Auth\Model\Programa', $usuario->programa);
            $modulo = $this->getObjectmanager()->find('Auth\Model\Modulo', $prog->id_modulo);
            $modulo = strtolower($modulo->url_modulo);
            $controller = strtolower($prog->controller_programa);
            return $this->redirect()->toUrl(BASE_URL . '/' . $modulo . '/' . $controller . '/index');
        }
    }

    public function selecionarPerfilAction() {
        $this->layout('layout/layoutBefore');
        $session = $this->getService('session');
        $id = $this->params()->fromRoute('id', 0);
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('email' => $this->getUser()->email));
        $perfil = $this->getService('Auth\Service\Perfil')->getPerfilPUsuario($usuario->id, $id);
        if (!$perfil) {
            $this->flashMessenger()->addInfoMessage('Seu usuário não está vinculado a um perfil, solicite ao administrador um vínculo!!!');
            return $this->redirect()->toUrl('/auth/auth');
        }
        if (count($perfil) == 1) {
            $session->offsetSet('role', $perfil[0]['desc_perfil']);
            return $this->redirect()->toUrl(BASE_URL.'/auth/selecionar-perfil/selecionar-programa/id/'.$perfil[0]['id']);
        }
        return new ViewModel(array(
            'perfil' => $perfil
        ));
    }

    public function carregarSessaoAction() {
        $id = $this->params()->fromRoute('id', 0);
        $programa = $this->getObjectmanager()->find('Auth\Model\Programa', $id);
        $modulo = $this->getObjectManager()->getRepository('Auth\Model\Modulo')->findOneBy(array('id' => $programa->id_modulo));
        $modulo = strtolower($modulo->url_modulo);
        $controller = strtolower($programa->controller_programa);

        return $this->redirect()->toUrl(BASE_URL . "/$modulo/$controller");
    }

}
