<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model Status Usuário
 * 
 * @category Estágios
 * @package Model
 * @author Cezar <cezar08@unochapeco.edu.br>
 */

/**
 * @ORM\Entity @ORM\Table(name = "public.dm_status_usuario")
 */
class StatusUsuario {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type = "integer")
     */
    protected $id;

    /**
     * @ORM\Column(type = "string")
     */
    protected $desc_status_usuario;

    public function setDesc_status_usuario($desc_status_usuario) {
        $this->desc_status_usuario = $desc_status_usuario;
    }

    public function getId() {
        return $this->id;
    }

    public function getDesc_status_usuario() {
        return $this->desc_status_usuario;
    }

}

?>