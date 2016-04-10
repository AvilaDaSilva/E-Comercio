<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;


//Author: Felippe S. R. dos Santos
//<felippe.omgt@gmail.com>
/**
 * @ORM\Entity @ORM\Table (name = "public.dm_mensagem_anexo")
 */
class AnexoMensagem {
    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Mensagens") 
     * @ORM\JoinColumn(name="mensagem", referencedColumnName="id")
     */
    protected $mensagem;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $nome_arquivo;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $tipo;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $tamanho;
    
    /**
     * @ORM\Column(type="blob")
     */
    protected $anexo;
    
    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

    protected $inputFilter;

    function getInputFilter() {
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
                        'name' => 'mensagem',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'nome_arquivo',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'tipo',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'tamanho',
                        'required' => false,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}