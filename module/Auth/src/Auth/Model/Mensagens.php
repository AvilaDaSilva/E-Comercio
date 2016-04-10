<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;


//Author: Felippe S. R. dos Santos
//<felippe.omgt@gmail.com>
/**
 * @ORM\Entity @ORM\Table (name = "public.dm_mensagens")
 */
class Mensagens {
    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Usuario") 
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id")
     */
    protected $usuario;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $assunto;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $mensagem;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $status;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $desc_status;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $tipo_mensagem;
    
    /**
     *
     * @ORM\Column(type="datetime")
     */
    protected $data_leitura;
    
    /**
     *
     * @ORM\Column(type="datetime")
     */
    protected $data_criacao;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $autor;
    
    /**
     * @ORM\ManyToOne(targetEntity="AutorizacaoMensagens", inversedBy="mensagem") 
     * @ORM\JoinColumn(name="solicitacao", referencedColumnName="id")
     */
    protected $solicitacao;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $favorito;
    
    /**
     *
     * @ORM\Column(type="integer")
     */
    protected $anexo;
    
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