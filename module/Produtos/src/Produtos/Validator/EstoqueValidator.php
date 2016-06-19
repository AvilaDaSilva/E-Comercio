<?php

namespace Admin\Validator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class CategoriaValidator extends InputFilter
{
    public function __construct()
    {
        $factory = new InputFactory();
        $this->add($factory->createInput(array(
            'name' => 'id',
            'required' => false,
            'filters' => array(
                array('name' => 'Int'),
            ),
        )));
        $this->add($factory->createInput(array(
            'name' => 'quantidade',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                )),
        )));
        $this->add($factory->createInput(array(
            'name' => 'operacao',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                )),
        )));
        $this->add($factory->createInput(array(
            'name' => 'quantidade_nova',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                )),
        )));
    }
}