<?php 

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.uf")
 *
 */
class Uf {
    
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;
    
    /** @ORM\Column(length=140, nullable=false) */
    protected $desc_uf;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
    * @return int
    */		
    function getId() {
        return $this->id;
    }

    /**
    * @return string
    */
    function getDescPerfil() {
        return $this->$desc_uf;
    }

    /**
    * @param int $id
    */
    function setId($id) {
        $this->id = $id;
    }

    /**
    * @param string $desc_perfil
    */
    function setDescPerfil($desc_uf) {
        $this->$desc_uf = $desc_uf;
    }
}