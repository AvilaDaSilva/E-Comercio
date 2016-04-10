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

class Filial extends Form {
    /*
     * @parans filiais type array
     */

    public function __construct($empresa) {
        parent::__construct('filial');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL . '/auth/filiais/save');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        foreach ($empresa as $e) {
            $selectEmpresas[$e['id']] = $e['nome_empresa'];
        }
        $this->add(array(
            'type' => 'select',
            'name' => 'id_empresa',
            'options' => array(
                'label' => 'Empresa de vinculo da filial:*',
                'empty_option' => 'SELECIONE UMA EMPRESA',
                'value_options' => $selectEmpresas,
                'class' => 'control-label',
            ),
            'attributes' => array(
                'id' => '$id_empresa',
                'class' => 'chosen-select label label-info form-control',
        )));

        $this->add(array(
            'name' => 'nome_filial',
            'type' => 'text',
            'options' => array(
                'label' => 'Nome da filial*',
            ),
            'attributes' => array(
                'id' => 'nome_filial',
                'class' => 'form-control demo-oi-errinput'
            )
        ));

        $this->add(array(
            'name' => 'desc_modulo_filial',
            'type' => 'text',
            'options' => array(
                'label' => 'Modulo Filial*',
            ),
            'attributes' => array(
                'id' => 'desc_modulo_filial',
                'class' => 'form-control demo-oi-errinput'
            )
        ));

        $this->add(array(
            'name' => 'desc_exibi_filial',
            'type' => 'text',
            'options' => array(
                'label' => 'Descrição para a aeibição da filial*',
            ),
            'attributes' => array(
                'id' => 'desc_exib_filial',
                'class' => 'form-control demo-oi-errinput'
            )
        ));

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'class' => 'btn btn-danger',
                'onclick' => "location.href='" . BASE_URL . "/auth/filiais/index'",
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));

        $this->add(array(
            'type' => 'file',
            'name' => 'url_img_filial',
            'options' => array(
                'class' => 'control-label',
                'label' => 'Enviar Arquivo*',
            ),
            'attributes' => array(
                'id' => 'url_img_filial',
                'enctype' => 'multipart/form-data',
                'class' => 'pull-left btn btn-default btn-file',
                'title' => 'Enviar Arquivo*',
                'placeholder' => 'Enviar Arquivo*',
            ),
        ));
    }

}

?>