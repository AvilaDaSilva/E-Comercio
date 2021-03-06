<?php 

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "usuario")
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
    
    /** @Column(length=140) */
    protected $senha;
    
    /** @Column(length=140) */
    protected $email;
    
     /**
     * @OneToOne(targetEntity="pessoa")
     * @JoinColumn(name="pessoa_id", referencedColumnName="id")
     */
    protected $pessoa;
    
    /**
     * @OneToMany(targetEntity="perfil")
     * @JoinColumn(name="perfil_id", referencedColumnName="id")
     */
    protected $perfil;
    
    
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
               




