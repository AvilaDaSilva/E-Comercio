<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/*
 * @package Model
 * @autor Alefe Variani <alefevariani@unochapeco.edu.br>
 * @autor:Maico Baggio <maico.baggio@unochapeco.edu.br>
 */

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_perfil")
 */
class Perfil {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Filial") 
     * @ORM\JoinColumn(name="id_filial", referencedColumnName="id")
     */
    protected $id_filial;

    /**
     * @ORM\OneToMany(targetEntity="ProgramaPerfil", mappedBy="id_perfil")
     *
     */
    protected $programa_perfil;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_perfil;
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
                        'name' => 'id_filial',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'Selecione uma Filial')
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_perfil',
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
                                    'message' => 'O campo Perfil deve ter mais que 4 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Perfil não pode estar vazio')
                            )
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}

?>