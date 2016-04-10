<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;


//Author: Felippe S. R. dos Santos
//<felippe.omgt@gmail.com>
/**
 * @ORM\Entity @ORM\Table (name = "public.dm_solicitacao")
 */
class AutorizacaoMensagens {
    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Usuario", cascade={"persist"}) 
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */
    protected $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="Acao") 
     * @ORM\JoinColumn(name="acao", referencedColumnName="id")
     */
    protected $acao;
    
    
//    protected $mensagem;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $pass;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $processo;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $flags;
       
    public function __set($key, $value){
        $this->$key = $value;
    }
    
    public function __get($key){
        return $this->$key;
    }
    
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
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    
}