<?php

namespace Auth\Form;

use Zend\Form\Fieldset;
use Auth\Model\Pessoa;
use Auth\Validator\PessoaValidator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class PessoaFieldSet extends Fieldset implements InputFilterProviderInterface {

    public function __construct() {
        parent::__construct('PessoaFieldSet');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Pessoa());
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'nome',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Nome*: '
            )
        ));

        $this->add(array(
            'name' => 'data_nascimento',
            'type' => 'date',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Data de Nascimento*: ',
                'format' => 'd/m/Y'
            )
        ));
        
        $this->add(array(
            'name' => 'sexo',
            'type' => 'radio',
            'options' => array(
                'value_options' => array(
                    'M' => 'Masculino',
                    'F' => 'Feminino',
                ),
                'label' => 'Sexo*: '
            )
        ));
        
        $this->add(array(
            'name' => 'endereco',
            'type' => 'Auth\Form\EnderecoFieldSet',
            'options' => array(
                'label' => 'Endereço: '
            )
        ));
    }

    public function getInputFilterSpecification() {
        return PessoaValidator::getValidation();
    }

}

?>