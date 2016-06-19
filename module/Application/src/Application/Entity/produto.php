<?php 

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "produto")
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
     * @OneToMany(targetEntity="avalicao", mappedBy="produto")
     */
    protected $desc_produto;
    
     /**
     * @OneToMany(targetEntity="Categoria", mappedBy="des_categoria")
     */
    protected $categoria;
    
    /** @Column(type="integer") */
    protected $valor;
    
    /** @Column(type="integer") */
    protected $avaliacao;
    
    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }
    /**
     * @return string $desc_produto
     */
    function getDesc_produto() {
        return $this->desc_produto;
    }
    /**
     * @return integer $categoria
     */
    function getCategoria() {
        return $this->categoria;
    }
    
    /**
     * @return integer $valor
     */
    function getValor() {
        return $this->valor;
    }

    /**
     * @return integer $avaliacao
     */
    function getAvaliacao() {
        return $this->avaliacao;
    }
    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }
    
    /**
     * @param string $desc_produto
     */
    function setDesc_produto($desc_produto) {
        $this->desc_produto = $desc_produto;
    }
    
    /**
     * @param integer $categoria
     */
    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }
    
    /**
     * @param integer $valor
     */
    function setValor($valor) {
        $this->valor = $valor;
    }
    
    /**
     * @param integer $avaliacao
     */
    function setAvaliacao($avaliacao) {
        $this->avaliacao = $avaliacao;
    }
   
}
