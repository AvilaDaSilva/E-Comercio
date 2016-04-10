<?php

/*
 * Model Modulo
 * @author Ricardo Farina <ricardofarina@unochapeco.edu.br>
 * @author Eduardo Capoani <eduardocapoani@unochapeco.edu.br>
 * 
 */

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * Entidade Modulo
 * 
 * @category Auth
 * @package Model
 */

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_modulo")
 */
class Modulo {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_modulo;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $url_modulo;

    /** configura os filtros dos campos da entidade
     * 
     */
    protected $inputFilter;

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

    public function getInputFilter() {

        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();



            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_modulo',
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
                                    'min' => 4,
                                    'max' => 80,
                                    'message' => 'O campo Modulo deve ter mais que 4 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Modulo não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'url_modulo',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 2,
                                    'max' => 80,
                                    'message' => 'O campo URL do Modulo deve ter mais que 2 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo URL do Modulo não pode estar vazio')
                            )
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
