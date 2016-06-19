<?php

namespace Produtos\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.estoque")
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
     * @ORM\OneToOne(targetEntity="Produto")
     * @ORM\JoinColumn(name="produto_id", referencedColumnName="id")
     */
    protected $produto;
    
    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     */
    protected $quantidade;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    function getId() {
        return $this->id;
    }

    function getProduto() {
        return $this->produto;
    }

    function getQuantidade() {
        return $this->quantidade;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProduto($produto) {
        $this->produto = $produto;
    }

    function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    function somar($val) {
        $this->quantidade = $this->quantidade + $val;
    }
    
    function subtrair($val) {
        $this->quantidade = $this->quantidade - $val;
    }

}

