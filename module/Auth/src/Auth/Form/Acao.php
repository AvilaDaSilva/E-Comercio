<?php

namespace Auth\Form;

use Zend\Form\Form;
 
/**
 * Form Ação
 * @data 13-01-2015
 * @category Auth
 * @package Controller 
 * @author  Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */
class Acao extends Form {

    public function __construct($filial) {
        parent::__construct('Acao');
        $this->setAttribute('method', 'POST');
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
                'class' => 'selectpicker form-control',
                'data-live-search'=> true
            ),
            'options' => array(
                'label' => 'Programa',
                'empty_option' => 'SELECIONE PRIMEIRO UMA FILIAL',
                'disable_inarray_validator' => true,
                'onchange' => "ajaxGet('" . BASE_URL . "/auth/acao/programas-acao/id/'+this.value, 'id_programa');",
            ),
        ));

        foreach ($filial as $f) {
            $selectFiliais[$f['id']] = $f['nome_filial'];
        }

        $this->add(array(
            'type' => 'select',
            'name' => 'id_filial',
            'options' => array(
                'label' => 'Filial',
                'empty_option' => 'SELECIONE UMA FILIAL',
                'value_options' => $selectFiliais,
                'class' => 'control-label'
            ),
            'attributes' => array(
                'id' => 'id_filial',
                'onchange' => "ajaxGet('" . BASE_URL . "/auth/acao/programas-acao/id/'+this.value, 'id_programa'); reload();",
                'class' => 'chosen-select label label-info form-control',
                
        )));

        $this->add(array(
            'name' => 'desc_acao',
            'type' => 'text',
            'options' => array(
                'label' => 'Descrição Ação*',
            ),
            'attributes' => array(
                'id' => 'desc_acao',
                'class' => 'form-control demo-oi-errinput'
            )
        ));
        
        $this->add(array(
            'name' => 'acao_url',
            'type' => 'text',
            'options' => array(
                'label' => 'Url Ação*'
            ),
            'attributes' => array(
                'id' => 'acao_url',
                'class' => 'form-control demo-oi-errinput'
        )));
        
        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
            	'class' => 'btn btn-danger',
                'onclick' => "location.href='" . BASE_URL . "/auth/acao/index'",
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }
}

?>
