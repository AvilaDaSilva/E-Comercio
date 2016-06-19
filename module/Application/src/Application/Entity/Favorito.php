<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "favorito")
 *
 */
class Favorito {

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
     * @ManyToOne(targetEntity="produto")
     * @JoinColumn(name="produto_id", referencedColumnName="id")
     */
    protected $produto;

    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }

    /**
     * @return integer $pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * @return integer $produto
     */
    function getProduto() {
        return $this->produto;
    }

    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @param integer $pessoa
     */
    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    /**
     * @param integer $produto
     */
    function setProduto($produto) {
        $this->produto = $produto;
    }
}
