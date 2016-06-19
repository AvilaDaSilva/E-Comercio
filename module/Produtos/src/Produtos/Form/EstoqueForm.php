<?php

namespace Produtos\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Produtos\Model\Estoque;

class EstoqueForm extends Form {

    public function __construct() {
        parent::__construct('EstoqueForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/produtos/estoques/save');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Estoque());

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'quantidade',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
                'disabled' => true
            ),
            'options' => array(
                'label' => 'Quantidade Atual*: '
            )
        ));
        
        $this->add(array(
            'name' => 'operacao',
            'type' => 'radio',
            'options' => array(
                'value_options' => array(
                    'S' => 'Somar',
                    'R' => 'Reduzir',
                ),
                'label' => 'Operação*: '
            )
        ));
        
        $this->add(array(
            'name' => 'quantidade_nova',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Quantidade Operacioanal*: '
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
                'href' => BASE_URL . '/produtos/categorias/index'
            ),
                )
        );
    }

}

?>