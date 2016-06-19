<?php

namespace Produtos\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.produto")
 *
 */
class Produto {
    
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
    protected $desc_produto;
    
    /**
     * @ORM\Column(type="blob", nullable=false)
     *
     * @var string
     */
    protected $imagem;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $avalhacao;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $detalhes;
    
    /**
     * @ORM\ManyToOne(targetEntity="Categoria")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    protected $categoria;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $valor;
    
    /**
     * @ORM\ManyToMany(targetEntity="Promocao", inversedBy="produtos")
     */
    protected $promocoes;
    
    public function __construct()
    {
        $this->promocoes = new ArrayCollection();
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    function getId() {
        return $this->id;
    }

    function getDescProduto() {
        return $this->desc_produto;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getValor() {
        return $this->valor;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescProduto($desc_produto) {
        $this->desc_produto = $desc_produto;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }
    
    function getPromocoes() {
        return $this->promocoes;
    }

    function setPromocoes($promocoes) {
        $this->promocoes = $promocoes;
    }

    function getDetalhes() {
        return $this->detalhes;
    }

    function setDetalhes($detalhes) {
        $this->detalhes = $detalhes;
    }
    
    function getAvalhacao() {
        return $this->avalhacao;
    }

    function setAvalhacao($avalhacao) {
        $this->avalhacao = $avalhacao;
    }
    
    function getImagem() {
        return $this->imagem;
    }

    function setImagem($imagem) {
        $this->imagem = $imagem;
    }
}

