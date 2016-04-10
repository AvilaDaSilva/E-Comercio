<?php

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\Log as FormLogs;
use Zend\Paginator\Paginator;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Paginator\Adapter\Collection as Adapter;


class LogsController extends ActionController {

    public function indexAction() {
    	$FormBuscar = new FormLogs();
    	$session = $this->getService('Session');
    	$request = $this->getRequest();
    	$page = (int) $this->params()->fromRoute('page', 0);
    	if ($request->isPost()){
    		$FormBuscar->setData($request->getPost());
    		if ($FormBuscar->isValid()) {
    			$data = $FormBuscar->getData();
    		}
    	} else {
    		$data = array(
    				'start' => date("d/m/Y", mktime(0, 0, 0, date('m'), date('d')-4, date('Y'))),
    				'end' => date("d/m/Y"),
    		);
    		$FormBuscar->setData($data);
    	}
    	$session->offsetSet('buscalogs', $data);
    	$service = $this->getService('Auth\Service\Log');
    	$json = $service->getAllLogs($session->offsetGet('buscalogs'));
    	//print_r($json); exit;
    	$jsonObject = json_encode($json);
    	$session->offsetSet('json', $jsonObject);
    	$collection = new ArrayCollection($json);
    	$paginator = new Paginator(new Adapter($collection));
    	$paginator->setCurrentPageNumber($this->params()->fromRoute('page'));
    	$paginator->setItemCountPerPage(ITENS_PER_PAGE);
    	return new ViewModel(array(
    			'usuarios' => $paginator,
    			'form' => $FormBuscar,
    			'json' => $json
    	));
    }

    public function usuariosNomeAction() {
        $buscar = mb_strtoupper($_GET['term'], 'UTF-8');
        $usuarios = $this->getService('Auth\Service\Log')->getUsuariosNome($buscar);
        $array = array();
        foreach ($usuarios as $chave => $c) {
            $array[$chave]['id'] = $c['id'];
            $array[$chave]['label'] = $c['nome'];
            $array[$chave]['value'] = $c['nome'];
        }
        echo json_encode($array);
        exit;
    }

}
