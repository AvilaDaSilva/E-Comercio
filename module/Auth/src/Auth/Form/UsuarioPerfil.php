<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UsuarioPerfil extends Form {

    public $checkbox;

    public function __construct($usuarios, $perfis) {

        parent::__construct('usuarioPerfil');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL . '/auth/usuarioperfil/save');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));

        $this->add(array(
            'name' => 'id_usuario',
            'type' => 'select',
            'options' => array(
                'class' => 'control-label',
                'label' => ' Usuarios* ',
                'value_options' => $usuarios,
            ),
            'attributes' => array(
                'id' => 'id_usuario',
                'class' => 'chosen-select label label-info form-control',
                'onchange' => "checkbox();"
            )
        ));

        $this->checkbox[] = array(
            'type' => 'checkbox',
            'name' => 'id_perfil[]',
            'id' => 'id_perfil[]',
            'label' => 'Perfis:* ',
            'id_div' => 'perfis_checkbox',
            'values' => $perfis,
        );

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