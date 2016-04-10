<?php

namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class Log extends Form {

    /**
     * @var inputFilter Zend\InputFilter\Factory
     */
    private $inputFilter;

    public function __construct() {
        parent::__construct('Logs');
        $this->setAttribute('method', 'post');
        $this->add(array(
        		'type' => 'text',
        		'name' => 'start',
        		'options' => array(
        				'label' => ' De ',
        		),
        		'attributes' => array(
        				'id' => 'start',
        				'class' => 'form-control',
        		)
        ));
        $this->add(array(
        		'type' => 'text',
        		'name' => 'end',
        		'options' => array(
        				'label' => 'até',
        		),
        		'attributes' => array(
        				'id' => 'end',
        				'class' => 'form-control',
        		)
        ));
        $this->setInputFilter($this->getInputFilter());
    }

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'start',
                        'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
            		'name' => 'end',
            		'required' => true,
            )));
            
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
?>