<?php 

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "perfil")
 *
 */
class Perfil {
    
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;
    
    /** @Column(length=140) */
    protected $desc_perfil;
    

    /**
    * @return int
    */		
    function getId() {
        return $this->id;
    }

    /**
    * @return string
    */
    function getDesc_perfil() {
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
    function setDesc_perfil($desc_perfil) {
        $this->desc_perfil = $desc_perfil;
    }

    
    
}
