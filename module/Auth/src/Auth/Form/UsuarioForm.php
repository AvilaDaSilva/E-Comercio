<?php

namespace Auth\Form;

use Zend\Form\Form;
use Auth\Model\Usuario;
use Zend\InputFilter\InputFilter;

class UsuarioForm extends Form {

    public function __construct() {
        parent::__construct('UsuarioForm');
        $this->setInputFilter(new InputFilter());
        
        $this->add(array(
            'name' => 'usuario',
            'type' => 'Auth\Form\UsuarioFieldSet',
            'options' => array(
                'label' => 'Dados de Autenticação: ',
                'use_as_base_fieldset' => true
            )
        ));
        
        $this->add(array(
            'name' => 'salvar',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'class' => 'btn btn-success text-uppercase'
            ),
                )
        );
        
        $this->add(array(
            'name' => 'cancelar',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Cancelar',
                'class' => 'btn btn-danger text-uppercase',
                'href' => BASE_URL . '/auth/usuarios/index'
            ),
                )
        );
    }

}

?>