<?php

/**
 * @author willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */

namespace Auth\Model;

//
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_programa")
 * 
 */
class Programa {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ProgramaPerfil", mappedBy="id_programa")
     * 
     */
    protected $programaPerfil;

    /**
     * @ORM\OneToMany(targetEntity="Acao", mappedBy="id_programa")
     * 
     */
    protected $acao;

    /**
     * @ORM\ManyToOne(targetEntity="Filial") 
     * @ORM\JoinColumn(name="id_filial", referencedColumnName="id")
     */
    protected $id_filial;

    /**
     * @ORM\ManyToOne(targetEntity="Modulo") 
     * @ORM\JoinColumn(name="id_modulo", referencedColumnName="id")
     */
    protected $id_modulo;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_programa;

    /**
     *
     * @ORM\Column(type="bigint")
     */
    protected $menu;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $icone;
    protected $inputFilter;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $controller_programa;

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
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));



            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_modulo',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_programa',
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
                                    'min' => 3,
                                    'max' => 80,
                                    'message' => 'O campo programa deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo programa não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'controller_programa',
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
                                    'min' => 3,
                                    'max' => 80,
                                    'message' => 'O campo Controllador programa deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Controlador programa não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'menu',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int')
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}

?>