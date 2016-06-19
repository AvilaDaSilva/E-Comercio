<?php

namespace Produtos\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Produtos\Model\Categoria;

class CategoriaForm extends Form {

    public function __construct() {
        parent::__construct('CategoriaForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/produtos/categorias/save');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Categoria());

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'desc_categoria',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Descrição*: '
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