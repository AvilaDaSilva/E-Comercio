<?php

namespace Auth\Validator;

class PessoaValidator
{
    public static function getValidation()
    {
        return array(
            'id' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
            'nome' => array(
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
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'data_nascimento' => array(
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
            ),
            'sexo' => array(
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
                            'max' => 1,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
        );
    }
}