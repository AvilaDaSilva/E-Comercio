<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
require 'public/xml_regex.php';

/**
 * Controlador Ação
 * @data 29-04-2013
 * @category Auth
 * @package Controller 
 * @author  Daniel
 */
class NoticiasController extends ActionController {
	private $url = 'http://idg.receita.fazenda.gov.br/noticias';
    /**
     * Index Controller com busca e paginação
     * @return void
     */
    public function indexAction() {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	   	curl_setopt($ch, CURLOPT_URL, $this->url);
    	$result = curl_exec($ch);
//    	echo '<pre>';
//		print_r(getNewsRF($result, 'div'));
    	//tileContent
    	curl_close($ch);
    	$session = $this->getService('Session');
    	$json = getNewsRF($result, 'div');
    	$jsonObject = json_encode($json);
    	$session->offsetSet('json', $jsonObject);
    	return new ViewModel(array(
    			'json' => $json
    	));
//    	exit;
    }
}