<?php

namespace Produtos\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Produtos\Model\Produto;

class ProdutoForm extends Form {

    public function __construct($em) {
        parent::__construct('ProdutoForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', BASE_URL . '/produtos/produtos/save');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Produto());

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'desc_produto',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Descrição*: '
            )
        ));
        
        $this->add(array(
            'name' => 'imagem',
            'type' => 'file',
            'options' => array(
                'label' => 'Imagem*: '
            )
        ));
        
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'categoria',
            'options' => array(
                'label' => 'Categoria*:',
                'object_manager' => $em,
                'target_class' => '\Produtos\Model\Categoria',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA CATEGORIA',
                'label_generator' => function($target){
                    return $target->getDescCategoria();
                }
            ),
        ));

        $this->add(array(
            'name' => 'valor',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Valor*: '
            )
        ));
        
        $this->add(array(
            'name' => 'detalhes',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Detalhes*: '
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