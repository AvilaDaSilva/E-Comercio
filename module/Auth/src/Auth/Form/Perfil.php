<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

/*
 * @package Form
 * @autor Alefe Variani <alefevariani@unochapeco.edu.br>
 * @autor:Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

class Perfil extends Form {
    /*
     * @parans perfis type array
     */

    public function __construct($filial) {

        parent::__construct('perfil');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL.'/auth/perfis/save');
        $this->setAttribute('id', 'validar');
        $this->add(array(
        		'name' => 'id',
        		'type' => 'hidden',
        ));
        $this->add(array(
        		'type' => 'text',
        		'name' => 'desc_perfil',
        		'options' => array(
        				'label' => 'Perfil*',
        				'class' => 'control-label'
        		),
        		'attributes' => array('size' => '80',
        				'class' => 'form-control',
        				'title' => 'Nome Perfil',
        				'placeholder' => 'Nome Perfil',
        				'data-error-message' => 'Nome Perfil')
        ));
        
		foreach($filial as $f){
            $selectFilial[$f['id']] = $f['nome_filial'];
        }
        $this->add(array(
            'type' => 'select',
            'name' => 'id_filial',
            'options' => array(
                'label' => 'Filial:*',
                'empty_option' => 'SELECIONE UMA FILIAL',
                'value_options' => $selectFilial,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'id' => 'id_filial',
                'class' => 'chosen-select label label-info form-control',
        )));
        $this->add(array(
        		'type' => 'button',
        		'name' => 'cancelar',
        		'attributes' => array(
        				'class' => 'btn btn-danger',
        				'onclick' => "location.href='" . BASE_URL . "/auth/perfis/index'",
        		),
        		'options' => array(
        				'label' => 'Cancelar'
        		)
        ));
    }
}
?>