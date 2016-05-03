<?php

namespace Auth\Validator;

class EnderecoValidator
{
    public static function getValidation()
    {
        return array(
            'id' => array(
               'required' => true,
               'filters' => array(
                   array('name' => 'Int'),
               ),

            ),
            'cep' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'rua' => array(
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
                            'max' => 140,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'cidade' => array(
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
                            'max' => 140,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'bairro' => array(
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
                            'max' => 140,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'uf' => array(
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
                            'max' => 2,
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'numero' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(),
            ),
            'complemento' => array(
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
                            'max' => 140,
                        ),
                    )
                ),
            ),
        );
    }
}