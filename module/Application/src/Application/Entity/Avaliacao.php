<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "avaliacao")
 *
 */
class Avaliacao {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;

    /** @Column(type="integer") */
    protected $estrelas;

    /**
     * @ManyToOne(targetEntity="produto")
     * @JoinColumn(name="produto_id", referencedColumnName="id")
     */
    protected $produto;

    /**
     * @OneToOne(targetEntity="pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;

    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }

    /**
     * @return integer $estrelas
     */
    function getEstrelas() {
        return $this->estrelas;
    }

    /**
     * @return integer $produto
     */
    function getProduto() {
        return $this->produto;
    }

    /**
     * @return integer $pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @param integer $estrelas
     */
    function setEstrelas($estrelas) {
        $this->estrelas = $estrelas;
    }

    /**
     * @param integer $produto
     */
    function setProduto($produto) {
        $this->produto = $produto;
    }

    /**
     * @param integer $pessoa
     */
    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

}
