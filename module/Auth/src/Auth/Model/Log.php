<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity @ORM\Table (name = "public.dm_log")
 */
class Log {

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="date")
     */
    protected $data;
    
    /**
     *
     * @ORM\Column(type="string")
     */
    protected $hora;

    /**
     * @ORM\ManyToOne(targetEntity="\Auth\Model\Usuario")
     * @ORM\JoinColumn(name="id_usuario",referencedColumnName="id") 
     */
    protected $usuario;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $acao;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $controlador;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $modulo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $params;

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

}
