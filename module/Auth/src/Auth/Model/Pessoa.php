<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.pessoa")
 *
 */
class Pessoa {
               
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
    protected $nome;
    
    /** @ORM\Column(type="datetime", name="data_nascimento", nullable=false) */
    protected $data_nascimento;
    
    /**
     * @ORM\OneToOne(targetEntity="Endereco")
     * @ORM\JoinColumn(name="endereco_id", referencedColumnName="id")
     */
    protected $endereco;
    
    /**
     * @ORM\Column(type="string", length=1, nullable=false)
     *
     * @var string
     */
    protected $sexo;
    
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
     * @return string $nome
     */
    function getNome() {
        return $this->nome;
    }
    /**
     * @return datetime $data_nascimento
     */
    function getDataNascimento() {
        return $this->data_nascimento;
    }

    /**
     * @return string $endereco
     */
    function getEndereco() {
        return $this->endereco;
    }

    /**
     * @return string $sexo
     */
    function getSexo() {
        return $this->sexo;
    }

    /**
     * @param int $id
     */
    function setId($id) {
        $this->id = $id;
    }

    /**
     * @param string $endereco
     */
    function setNome($nome) {
        $this->nome = $nome;
    }

    /**
    * @param datetime $data_nascimento
    */
    function setDataNascimento($data_nascimento) {
        $this->data_nascimento = $data_nascimento;
    }

    /**
    * @param string $endereco
    */
    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    /**
    * @param string $sexo
    */
    function setSexo($sexo) {
        $this->sexo = $sexo;
    }


    

}

