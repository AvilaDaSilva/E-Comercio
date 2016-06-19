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
            'name' => 'desc_promocao',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper',
                    'options' => array('encoding' => 'UTF-8')
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                )),
        )));
        $this->add($factory->createInput(array(
            'name' => 'desconto',
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
            'name' => 'data_inicial',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper',
                    'options' => array('encoding' => 'UTF-8')
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'd/m/Y'
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                )
            ),
        )));
        $this->add($factory->createInput(array(
            'name' => 'data_final',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'StringToUpper',
                    'options' => array('encoding' => 'UTF-8')
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'd/m/Y'
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                )
            ),
        )));
        $this->add($factory->createInput(array(
            'name' => 'data_final',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                )
            ),
        )));
    }
}