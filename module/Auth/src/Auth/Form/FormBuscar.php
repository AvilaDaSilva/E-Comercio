<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Controlador Pessoas
 * @data 23-04-2013
 * @category Auth 
 * @package Form 
 * @author  Leomar Vaz Sartor <leomar_sartor@unochapeco.edu.br>
 * @author  Amanda Marcon <mandinha@unochapeco.edu.br>
 */
class FormBuscar extends Form {

    /**
     * @var inputFilter Zend\InputFilter\Factory
     */
    private $inputFilter;

    public function __construct($placeholder = null, $placeholder2 = null) {
        parent::__construct('FormBuscar');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '');

        $class = null;
        $readonly = null;
        

        if (isset($placeholder) && !isset($placeholder2)) {
            $class = 'calendario';
            $readonly = 'true';
        }
        if (isset($placeholder2) && !isset($placeholder)) {
            $placeholder = $placeholder2;
        }
        $this->add(array(
            'name' => 'busca',
            'required' => true,
            'attributes' => array(
                'id' => 'busca',
                'placeholder' => $placeholder,
                'class' => $class,
                'readonly' => $readonly
            )
        ));

        $this->add(array(
            'name' => 'buscar',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Buscar',
                'id' => 'submitBusca',
                'class' => 'btn',
                'title' => 'Buscar'
        )));

        $this->add(array(
            'name' => 'limpar_busca',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Limpar busca',
                'onclick' => 'limparBusca();',
                'class' => 'btn',
                'title' => 'Limpar Busca'
        )));




        $this->setInputFilter($this->getInputFilter());
    }

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();



            $inputFilter->add($factory->createInput(array(
                        'name' => 'busca',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            )
                        ),
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}

?>