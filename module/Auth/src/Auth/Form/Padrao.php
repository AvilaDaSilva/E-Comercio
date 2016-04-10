<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * Form Paciente
 * @data 31-10-2014
 * @category Scf
 * @package Controller 
 * @author  Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */
class Padrao extends Form {

    public function __construct($programa) {

        parent::__construct('padrao');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');

        $selectPrograma = array();
        foreach ($programa as $p) {
            if($p['menu']!=1)
                $selectPrograma[$p['id']] = $p['desc_programa'];
        }
        $this->add(array(
            'type' => 'select',
            'name' => 'programa',
            'options' => array(
                'label' => 'Programas*  ',
            	'class' => 'control-label',
            	//'placeholder'=>'Choose a Country...',
                'empty_option' => 'Selecione um programa para ser o padrão',
                'value_options' => $selectPrograma,
                'disable_inarray_validator' => true
            ),
            'attributes' => array(
                'id' => 'programa',
            	'class' => 'chosen-select label label-info form-control',
            )
        ));

       /* $this->add(array(
            'name' => 'botao',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Salvar',
                'id' => 'submitbutton',
                'class' => 'btn btn-primary',
        )));*/

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'onclick' => "location.href='" . BASE_URL . "/auth'",
                'class' => 'btn btn-danger'
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
                        'name' => 'programa',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}
