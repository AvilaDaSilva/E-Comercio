<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class Mensagens extends Form {

    protected $inputFilter;

    public function __construct($usuarios) {
        parent::__construct('mensagens');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');
        $this->setAttribute('enctype', 'multipart/form-data');


        $this->add(array(
            'type' => 'hidden',
            'name' => 'id',
        ));

        
        $this->add(array(
        'type' => 'text',
        'name' => 'tags',
        'options' => array(
            'label' => 'Para:'
        ),
        'attributes' => array(
            'id' => 'tags',
            'class' => 'form-control demo-oi-errinput',
            'data-role' => "tagsinput",
        )));
        
        foreach ($usuarios as $t) {
            $selectUsuarios[$t->nome] = $t->nome;
        }
        $this->add(array(
            'name' => 'usuario',
            'type' => 'select',
            'options' => array(
                'label' => 'Selecione os usuarios',
                'empty_option' => 'SELECIONAR OU BUSCAR UM USUÁRIO',
                'value_options' => $selectUsuarios,
                'class' => 'control-label' 
            ),
            'attributes' => array(
//                'multiple' => 'multiple',
                'onchange' => 'tagar($JQuery(this));',
                'class' => 'chosen-select label label-info form-control',
                'id' => 'users',
            )
        ));
        $this->add(array(
            'type' => 'text',
            'name' => 'assunto',
            'options' => array(
                'class' => 'control-label',
                'label' => 'Assunto'
            ),
            'attributes' => array(
                'id' => 'assunto',
                'class' => 'form-control demo-oi-errinput',
                'title' => 'Assunto da mensagem',
                'placeholder' => 'Digite aqui o assunto',
            ),
        ));

        $this->add(array(
            'type' => 'file',
            'name' => 'anexo[]',
            'options' => array(
                'class' => 'control-label',
                'label' => 'Anexar arquivos',
            ),
            'attributes' => array(
                'id' => 'anexo[]',
                'enctype' => 'multipart/form-data',
                'class' => 'pull-left btn btn-default btn-file',
//                'title' => 'Selecionar Arquivos',
                'placeholder' => 'Anexo',
                'multiple' => true,
        )));

        $this->add(array(
            'type' => 'textarea',
            'name' => 'mensagem',
            'options' => array(
                'class' => 'control-label',
                'label' => 'Mensagem'
            ),
            'attributes' => array(
                'id' => 'mensagem',
                'class' => 'form-control demo-oi-errinput',
                'title' => 'Corpo da mensagem',
                'placeholder' => 'Digite aqui sua mensagem',
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'tipo_mensagem',
            'options' => array(
                'label' => 'TIPO DA MENSAGEM',
                'empty_option' => 'SELECIONE O TIPO DA MENSAGEM',
                'disable_inarray_validator' => true,
                'value_options' => array(
                    'Alerta' => 'Alerta',
                    'Notificação' => 'Notificação',
                    'Mensagem' => 'Mensagem',
                    'Solicitação' => 'Solicitação',
                ),
                'class' => 'control-label'
            ),
            'attributes' => array(
                'id' => 'tipo_mensagem',
                'class' => 'chosen-select label label-info form-control',
            ),
        ));
        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'onclick' => "location.href='" . BASE_URL . "/auth/mensagens",
                'class' => 'btn btn-danger'
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }

    public function getFilters() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'assunto',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'mensagem',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'usuario',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'tipo_mensagem',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
