<?php

namespace Application\Form;

use Zend\Form\Form;

class CategoriaForm extends Form
{
    public function __construct()
    {
        parent::__construct('SugestaoForm');
        $this->setAttribute('method', 'POST');
        $this->add(
            array(
                'name' => 'id',
                'type' => 'hidden'
            )
        );
        $this->add(
            array(
                'name' => 'titulo',
                'type' => 'text',
                'options' => array(
                    'label' => 'Titulo*:'
                ),
                'attributes' => array(
                  'placeholder' => 'Informe o titulo aqui'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'sugestao',
                'type' => 'text',
                'options' => array(
                    'label' => 'Sugestão*:'
                ),
                'attributes' => array(
                  'placeholder' => 'Informe a sugestão aqui'
                ),
            )
        );
        $this->add(
            array(
                'name' => 'Salvar',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Salvar'
                )
            )
        );
    }
}