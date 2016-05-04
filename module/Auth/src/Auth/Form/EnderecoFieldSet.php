<?php

namespace Auth\Form;

use Zend\Form\Fieldset;
use Auth\Model\Endereco;
use Auth\Validator\EnderecoValidator;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class EnderecoFieldSet extends Fieldset implements InputFilterProviderInterface {
    
    public function __construct() {
        parent::__construct('EnderecoFieldSet');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Endereco());
        
        $uf = [
            1 => 'Acre',
            2 => 'Alagoas',
            3 => 'Amapá',
            4 => 'Amazonas',
            5 => 'Bahia',
            6 => 'Ceará',
            7 => 'Distrito Federal',
            8 => 'Espírito Santo',
            9 => 'Goiás',
            10 => 'Maranhão',
            11 => 'Mato Grosso',
            12 => 'Mato Grosso do Sul',
            13 => 'Minas Gerais',
            14 => 'Pará',
            15 => 'Paraíba',
            16 => 'Paraná',
            17 => 'Piauí',
            18 => 'Rio de Janeiro',
            19 => 'Rio Grande do Norte',
            20 => 'Rio Grande do Sul',
            21 => 'Rondônia',
            22 => 'Roraima',
            23 => 'Santa Catarina',
            24 => 'São Paulo',
            25 => 'Sergipe',
            26 => 'Tocantins'
        ];
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'cep',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Cep*: '
            )
        ));

        $this->add(array(
            'name' => 'rua',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Rua*: '
            )
        ));
        
        $this->add(array(
            'name' => 'cidade',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Cidade*: '
            )
        ));
        
        $this->add(array(
            'name' => 'bairro',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Bairro*: '
            )
        ));
        
        $this->add(array(
            'name' => 'uf',
            'type' => 'select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Uf*: ',
                'value_options' => $uf
            )
        ));
        
        $this->add(array(
            'name' => 'numero',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Número: '
            )
        ));
        
        $this->add(array(
            'name' => 'complemento',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Complemento: '
            )
        ));
    }

    public function getInputFilterSpecification() {
        return EnderecoValidator::getValidation();
    }

}

?>