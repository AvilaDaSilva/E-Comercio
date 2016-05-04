<?php 

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.perfil")
 *
 */
class Perfil {
    
    const ADMIN = 1;
    const CLIENTE = 2;
    
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;
    
    /** @ORM\Column(length=140, nullable=false) */
    protected $desc_perfil;
    
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
        return $this->desc_perfil;
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
    function setDescPerfil($desc_perfil) {
        $this->desc_perfil = $desc_perfil;
    }

    
    
}
