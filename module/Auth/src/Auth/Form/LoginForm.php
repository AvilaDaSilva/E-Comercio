<?php

namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct() {
        parent::__construct('Login');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/auth/auth/login');

        $this->add(array(
            'name' => 'login',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Login'
            ),
        ));

        $this->add(array(
            'name' => 'senha',
            'attributes' => array(
                'class' => 'form-control',
                'type' => 'password',
                'placeholder' => 'Senha'
            ),
        ));

        $this->add(array(
            'name' => 'Submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Entrar',
                'class' => 'btn btn-success text-uppercase'
            ),
                )
        );
        $this->add(array(
            'type' => 'checkbox',
            'name' => 'checkbox',
            'options' => array(
                'label' => 'Lembrar-me',
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
            'attributes' => array(
                'id' => 'checkbox',
            )
        ));
    }

}

?>