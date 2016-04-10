<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class RecuperaSenha extends Form {

    public function __construct() {
        parent::__construct('Senha');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/auth/auth/recupera-senha');
        $this->setAttribute('class', 'form-signin');

        $this->add(array(
            'name' => 'login',
            'type' => 'text',
            'attributes' => array(
            	'class' => 'form-control',
                'placeholder' => 'Login',
                'id' => 'login',
            ),
        ));

        $this->add(array(
            'name' => 'Submit',
            'attributes' => array(
            	'class' => 'btn btn-success text-uppercase',
                'type' => 'submit',
                'value' => 'Enviar',
            ),
                )
        );

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'onclick' => "location.href='/auth'",
                'class' => 'btn btn-danger text-uppercase',
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }
    
    protected $inputFilter;

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'login',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 80,
                                    'message' => 'O campo Login deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo login não pode estar vazio')
                            ),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
