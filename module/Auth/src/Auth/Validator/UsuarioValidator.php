<?php

namespace Auth\Validator;

class UsuarioValidator
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
            'email' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'StringToLower',
                        'options' => array('encoding' => 'UTF-8')
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                    )
                ),
            ),
            'senha' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
            ),
        );
    }
}