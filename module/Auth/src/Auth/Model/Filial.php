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
 * @ORM\Entity @ORM\Table (name = "public.dm_filial")
 */
class Filial {

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
    protected $nome_filial;

    /**
     * @ORM\OneToMany(targetEntity="Perfil", mappedBy="id_filial")
     * 
     */
    protected $perfil;

    /**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="id_empresa", referencedColumnName="id")
     */
    protected $id_empresa;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_modulo_filial;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_exibi_filial;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $url_img_filial;
    
    protected $inputFilter;

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }
    function getNome_filial() {
        return $this->nome_filial;
    }

    function setNome_filial($nome_filial) {
        $this->nome_filial = $nome_filial;
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
                        'name' => 'nome_filial',
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
                                    'message' => 'O campo Filial deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Filial não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_empresa',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array(
                                    'message' => 'O campo Programa não pode estar vazio'
                                )
                            )
                        )
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_modulo_filial',
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
                                    'message' => 'O campo modulo filial deve ter mais que 4 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo modulo filial não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_exibi_filial',
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
                                    'message' => 'O campo descrição filial deve ter mais que 4 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo descrição filial não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'url_img_filial',
                        'required' => false,
                        'options' => array('message' => 'O campo Imagem não pode estar vazio')
                    ))
            );

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}

?>
