<?php

namespace Produtos\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.categoria")
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
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $desc_categoria;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    function getId() {
        return $this->id;
    }

    function getDescCategoria() {
        return $this->desc_categoria;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescCategoria($desc_categoria) {
        $this->desc_categoria = $desc_categoria;
    }


}

