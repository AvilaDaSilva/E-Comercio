<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "sugestao")
 *
 */
class Sugestao {
               
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;
    
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */      
    protected $titulo;            
    
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $sugestao;
    
    /**
     * @return int
     */
    function getId() {
        return $this->id;
    }
    
    /**
     * @return int $pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }
    
    /**
     * @return string $titulo
     */
    function getTitulo() {
        return $this->titulo;
    }
    
    /**
     * @return string $sugestao
     */
    function getSugestao() {
        return $this->sugestao;
    }

    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }
    
    /**
     * @param int $pessoa
     */
    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }
    
    /**
     * @param string $titulo
     */
    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    /**
     * @param string $sugestao
     */
    function setSugestao($sugestao) {
        $this->sugestao = $sugestao;
    }    
}