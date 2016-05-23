<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "categoria")
 *
 */
class Categoria {
/**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;    
    
    /**
     * @ManyToOne(targetEntity="des_categoria")
     * @JoinColumn(name="id", referencedColumnName="id")
     */
    protected $desc_categoria;
    
    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }
    
    /**
     * @return string $desc_categoria
     */
    function getDesc_categoria() {
        return $this->desc_categoria;
    }
    
    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }
    
    /**
     * @param string $desc_categoria
     */
    function setDesc_categoria($desc_categoria) {
        $this->desc_categoria = $desc_categoria;
    }
    
}