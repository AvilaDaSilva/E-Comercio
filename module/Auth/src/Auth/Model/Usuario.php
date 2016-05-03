<?php 

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.usuario")
 *
 */
class Usuario {
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;    
    
    /** @ORM\Column(length=140, nullable=false) */
    protected $senha;
    
    /** @ORM\Column(type="string", length=140, nullable=false) */
    protected $email;
    
     /**
     * @ORM\OneToOne(targetEntity="Pessoa")
     * @ORM\JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;
    
    /**
     * @ORM\OneToOne(targetEntity="Perfil")
     * @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")
     */
    protected $perfil;
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    /**
     * @return int $id
     */
    function getId() {
        return $this->id;
    }

    /**
     * @return string $senha
     */
    function getSenha() {
        return $this->senha;
    }
    /**
     * @return string $email
     */
    function getEmail() {
        return $this->email;
    }

    /**
     * @return string $pessoa
     */
    function getPessoa() {
        return $this->pessoa;
    }

    /**
     * @return string $perfil
     */
    function getPerfil() {
        return $this->perfil;
    }
    
    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $senha
     */
    function setSenha($senha) {
        $this->senha = $senha;
    }

    /**
     * @param string $email
     */
    function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string $pessoa
     */
    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    /**
     * @param string $perfil
     */
    function setPerfil($perfil) {
        $this->perfil = $perfil;
    }
    
}
               




