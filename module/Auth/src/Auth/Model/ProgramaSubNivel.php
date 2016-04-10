<?php

namespace Auth\Model;

//
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_programa_nivel")
 *
 */
class ProgramaSubNivel {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Programa")
     * @ORM\JoinColumn(name="id_programa", referencedColumnName="id")
     */
    protected $id_programa;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_programa_nivel;

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
    protected $controller_programa_nivel;

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
                'name' => 'id_programa',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'desc_programa_nivel',
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
                'name' => 'controller_programa_nivel',
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

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}

?>