<?php

/*
 * Model Empresa
 * @author Rodrigo Angelo Valentini <rodrigoangelo@unochapeco.edu.br>
 * @author Huilson José Lorenzi <huilson@unochapeco.edu.br>
 * 
 */

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;


/**
 * Entidade Empresa
 * 
 * @category Auth
 * @package Controller
 */

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_empresa")
 */
class Empresa {

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
    protected $nome_empresa;
    protected $inputFilter;

    public function __set($key, $value){
        $this->$key = $value;
    }
    
    public function __get($key){
        return $this->$key;
    }

    /** configura os filtros dos campos da entidade
     */
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
                        'name' => 'nome',
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
                                    'max' => 80,
                                    'message' => 'O campo Nome da Empresa deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Nome da Empresa não pode estar vazio')
                            )
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}

?>