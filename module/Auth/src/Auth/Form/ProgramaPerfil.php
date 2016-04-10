<?php

namespace Auth\Form;

use Zend\Form\Form;

class ProgramaPerfil extends Form {

    public function __construct($filial, $perfis) {
        parent::__construct('ProgramaPerfil');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL.'/auth/programasperfis/save');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
      
        $this->add(array(
            'type' => 'select',
            'name' => 'id_programa',
            'attributes' => array(
                'id' => 'id_programa',
                'onchange' => "checkbox()",
                'class' => 'chosen-select label label-info form-control',
                ),
            'options' => array(                   
                'label' => 'Programa*',                                   
                'empty_option' => 'SELECIONE PRIMEIRO UMA FILIAL',
                'disable_inarray_validator' => true,    
            ),                
        ));
        //Select para Filial
        
        foreach($filial as $f){
            $selectFilial[$f['id']] = $f['nome_filial'];
        }
        
        $this->add(array(
            'type' => 'select',
            'name' => 'id_filial',
            'options' => array(
                'label' => 'Filial:*',
                'empty_option' => 'SELECIONA UMA FILIAL',
                'value_options' => $selectFilial,
            ),
            'attributes' => array(
                'id' => 'id_filial',
                'onchange' => "change(); reload();",
                'class' => 'chosen-select label label-info form-control',
        )));
        
        $selectPerfis = array();
        foreach($perfis as $p){
            $selectPerfis[$p['id']] = $p['desc_perfil'];
        }
        
        $this->checkbox[] = array(
        		'type' => 'checkbox',
        		'name' => 'id_perfil[]',
        		'id' => 'id_perfil[]',
        		'label' => 'Perfis:* ',
        		'values' => $selectPerfis,
        );
        $this->add(array(
                'type' => 'hidden',
                'name' => 'perfil',
            ));

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
            	'class' => 'btn btn-danger',
                'onclick' => "location.href='" . BASE_URL . "/auth/programasperfis/index'",
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }

}

?>
