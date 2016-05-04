<?php

namespace Auth\Form;

use Zend\Form\Fieldset;
use Auth\Model\Usuario;
use Auth\Validator\UsuarioValidator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class UsuarioFieldSet extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {
        parent::__construct('UsuarioFieldSet');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Usuario());
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'email',
            'type' => 'email',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'E-mail*: '
            )
        ));
        
        $this->add(array(
            'name' => 'senha',
            'type' => 'password',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Senha*: '
            )
        ));
        
        $this->add(array(
            'name' => 'pessoa',
            'type' => 'Auth\Form\PessoaFieldSet',
            'options' => array(
                'label' => 'Dados Pessoais: '
            )
        ));
    }
    
    public function getInputFilterSpecification() {
        
        return UsuarioValidator::getValidation();
    }

}

?>