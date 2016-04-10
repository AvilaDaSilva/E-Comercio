<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class NovaSenha extends Form {

    public function __construct($usuario, $hash) {
        parent::__construct('NovaSenha');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/auth/auth/nova-senha/hash/'.$hash);
        $this->setAttribute('class', 'form-signin');

        $this->add(array(
            'type' => 'text',
            'name' => 'login',
            'attributes' => array(
                'id' => 'nome',
                'readonly' => true,
            	'class' => 'form-control',
                'value' => $usuario->login,
        )));
        
        $this->add(array(
            'type' => 'password',
            'name' => 'senha',
             'attributes' => array(
                'id' => 'senha',
             	'class' => 'form-control',
             	'placeholder' => 'Nova senha*',
            ),
        ));
         

        $this->add(array(
            'name' => 'Submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Enviar',
                'class' => 'btn btn-success text-uppercase'
            ),
                )
        );

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'onclick' => "location.href='" . BASE_URL . "/auth'",
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
                                    'message' => 'O campo login deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo login não pode estar vazio')
                            ),
                        ),
            )));
            
            
            $inputFilter->add($factory->createInput(array(
                        'name' => 'senha',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 6,
                                    'max' => 80,
                                    'message' => 'O campo Senha deve ter mais que 6 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Senha não pode estar vazio')
                            ),
                        ),
            )));
                      
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
