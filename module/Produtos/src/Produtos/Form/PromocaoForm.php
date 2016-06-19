<?php

namespace Produtos\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
use Produtos\Model\Promocao;

class PromocaoForm extends Form {

    public function __construct($em) {
        parent::__construct('PromocaoForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', BASE_URL . '/produtos/promocoes/save');
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setObject(new Promocao());

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            )
        );
        
        $this->add(array(
            'name' => 'desc_promocao',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Descrição*: '
            )
        ));
        
        $this->add(array(
            'name' => 'desconto',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Desconto*: '
            )
        ));
        
        $this->add(array(
            'name' => 'data_inicial',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Data de Início*: '
            )
        ));
        
        $this->add(array(
            'name' => 'data_final',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Data Término*: '
            )
        ));
        
        $this->add(array(
            'name' => 'status_promocao',
            'type' => 'select',
            'options' => array(
                'value_options' => array(
                    true => 'Ativa',
                    false => 'Inativa',
                ),
                'label' => 'Status da Promoção*: '
            )
        ));
        
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'produtos',
            'options' => array(
                'label' => 'Produtos*:',
                'object_manager' => $em,
                'target_class' => '\Produtos\Model\Produto',
                'property' => 'desc_produto',
                'label_generator' => function($target){
                    return $target->getDescProduto();
                }

            ),
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
                'href' => BASE_URL . '/produtos/promocoes/index'
            ),
                )
        );
    }

}

?>