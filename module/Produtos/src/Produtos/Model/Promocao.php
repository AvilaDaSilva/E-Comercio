<?php

namespace Produtos\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.promocao")
 *
 */
class Promocao {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $desc_promocao;
    
   /**
    * @ORM\Column(type="float", nullable=false)
    */
    protected $desconto;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $data_inicial;
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $data_final;
    
    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var string
     */
    protected $status_promocao;
    
    /**
     * @ORM\ManyToMany(targetEntity="Produto", mappedBy="promocoes")
     * @ORM\JoinTable(name="ecommerce.promocao_produto")
     */
    protected $produtos;
    
    public function __construct()
    {
        $this->produtos = new ArrayCollection();
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    function getId() {
        return $this->id;
    }

    function getDescPromocao() {
        return $this->desc_promocao;
    }

    function getDesconto() {
        return $this->desconto;
    }

    function getDataInicial() {
        return $this->data_inicial;
    }

    function getDataFinal() {
        return $this->data_final;
    }

    function getStatusPromocao() {
        return $this->status_promocao;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescPromocao($desc_promocao) {
        $this->desc_promocao = $desc_promocao;
    }

    function setDesconto($desconto) {
        $this->desconto = $desconto;
    }

    function setDataInicial($data_inicial) {
        $this->data_inicial = $data_inicial;
    }

    function setDataFinal($data_final) {
        $this->data_final = $data_final;
    }

    function setStatusPromocao($status_promocao) {
        $this->status_promocao = $status_promocao;
    }
    
    function getProdutos() {
        return $this->produtos;
    }

    function setProdutos($produtos) {
        $this->produtos = $produtos;
    }

}

