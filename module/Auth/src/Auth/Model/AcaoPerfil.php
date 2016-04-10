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
 * @ORM\Entity @ORM\Table(name = "public.dm_programa_perfil_acao")
 */
class AcaoPerfil {


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type = "integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ProgramaPerfil", inversedBy="acao_perfil") 
     * @ORM\JoinColumn(name="id_programa_perfil", referencedColumnName="id")
     */
    protected $id_programa_perfil;

    /**
     * @ORM\ManyToOne(targetEntity="Acao") 
     * @ORM\JoinColumn(name="id_acao", referencedColumnName="id")
     */
    protected $id_acao;

    public function __set($key, $value){
        $this->$key = $value;
    }
    
    public function __get($key){
        return $this->$key;
    }

    /*
     * Configura os filtros dos campos da entidade
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
                        'name' => 'id_programa_perfil',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_acao',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
