<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "estoque")
 *
 */
class Estoque {
/**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;    
    
    /**
     * @OneToOne(targetEntity="produto", inversedBy="desc_produto")
     * @JoinColumn(name="produto_id", referencedColumnName="id")
     */
    protected $produto;
            
    /** @Column(type="integer") */
    protected $quantidade;
            
    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }
    
    /**
     * @return integer $produto
     */
    function getProduto() {
        return $this->produto;
    }

    /**
     * @return integer $quantidade
     */    
    function getQuantidade() {
        return $this->quantidade;
    }
    
    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
    * @param integer $produto
    */
    function setProduto($produto) {
        $this->produto = $produto;
    }
    
    /**
     * @param interger $quantidade
     */
    function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

}    
            