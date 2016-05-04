<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "pessoa")
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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $nome;
    
    /** @Column(type="datetime", name="data_nascimento") */
    protected $data_nascimento;
    
    /** @Column(type="integer") */
    protected $endereco;
    
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $sexo;
    
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
    function getData_nascimento() {
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
    function setData_nascimento($data_nascimento) {
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

