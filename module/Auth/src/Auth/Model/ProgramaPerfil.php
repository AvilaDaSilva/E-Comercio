<?php

/*
 * Model Modulo
 * @author Ricardo Farina <ricardofarina@unochapeco.edu.br>
 */

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;


/**
 * Entidade Modulo
 *
 * @category Auth
 * @package Model
 */

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_programa_perfil")
 */
class ProgramaPerfil {
 
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
     * @ORM\ManyToOne(targetEntity="Perfil", inversedBy="programa_perfil") 
     * @ORM\JoinColumn(name="id_perfil", referencedColumnName="id")
     * 
     */
    protected $id_perfil;

     /**
     *
     * @ORM\OneToMany(targetEntity="AcaoPerfil", mappedBy="id_programa_perfil")
     */
    protected $acao_perfil;

    protected $inputFilter;

    public function __construct() {
        $this->acao_perfil = new ArrayCollection();
    }
    
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
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
                        
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_perfil',
                        'required' => false,
            )));

               $inputFilter->add($factory->createInput(array(
                        'name' => 'id_filial',
                        'required' => true,       
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
