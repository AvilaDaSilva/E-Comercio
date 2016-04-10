<?php

namespace Auth\Form;

use Zend\Form\Form;

class Programa extends Form {
    /*
     * @parans filiais type array
     */

    public function __construct($filial, $modulo) {

        parent::__construct('programa');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL . '/auth/programas/save');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        foreach ($filial as $f) {
            $selectFilial[$f['id']] = $f['nome_filial'];
        }

        $this->add(array(
            'type' => 'select',
            'name' => 'id_filial',
            'options' => array(
                'label' => 'Filial:*',
                'empty_option' => 'SELECIONA UMA FILIAL',
                'value_options' => $selectFilial,
                'class' => 'control-label'
            ),
            'attributes' => array(
                'id' => 'id_filial',
                'class' => 'chosen-select label label-info form-control',
        )));

        foreach ($modulo as $m) {
            $selectModulo[$m['id']] = $m['desc_modulo'];
        }

        $this->add(array(
            'type' => 'select',
            'name' => 'id_modulo',
            'options' => array(
                'label' => 'Modulo:*',
                'empty_option' => 'SELECIONA UM MODULO',
                'value_options' => $selectModulo,
            ),
            'attributes' => array(
                'id' => 'id_modulo',
                'class' => 'chosen-select label label-info form-control',
        )));

        $this->add(array(
            'name' => 'desc_programa',
            'type' => 'text',
            'options' => array(
                'label' => 'Programa*',
            ),
            'attributes' => array(
                'id' => 'desc_programa',
                'class' => 'form-control demo-oi-errinput'
        )));

       $this->add(array(
           'name' => 'controller_programa',
           'type' => 'text',
           'options' => array(
               'label' => 'Controlador do programa*'
           ),
           'attributes' => array(
               'id' => 'desc_programa',
               'class' => 'form-control demo-oi-errinput'
       )));
        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
            	'class' => 'btn btn-danger',
                'onclick' => "location.href='" . BASE_URL . "/auth/programas/index'",
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
        
        $this->checkbox[] = array(
        		'type' => 'checkbox',
        		'name' => 'menu',
        		'id' => 'menu',
        		'label' => '',
        		'values' => array(1 => "Não mostrar este programa no menu"),
        );
    }

}

?>