<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
 
/**
 * Entidade do controller.
 * 
 * @category Auth 
 * @package Model
 * @author  Daniel
 */

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_acao", uniqueConstraints={@ORM\UniqueConstraint(name="acao_programa_idx", columns={"acao_url", "id_programa"})})
 */
class Acao {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Programa")
     * @ORM\JoinColumn(name="id_programa", referencedColumnName="id")
     */
    protected $id_programa;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_acao;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $acao_url;
    protected $inputFilter;

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
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
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_programa',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo programa deve ser selecionado')
                            )
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'desc_acao',
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
                                    'message' => 'O campo Descrição ação deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Descrição ação não pode estar vazio')
                            )
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'acao_url',
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
                                    'message' => 'O campo Url ação deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo url ação não pode estar vazio')
                            )
                        ),
            )));


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}

?>
