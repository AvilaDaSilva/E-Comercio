<?php 

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;

/** @ORM\MappedSuperclass */

/**
 * @ORM\Entity
 * @ORM\Table (name = "ecommerce.endereco")
 *
 */
class Endereco {
    
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $id;
    
    /** @ORM\Column(type="string", length=140, nullable=false) */
    protected $rua;
    
    /** @ORM\Column(type="string", length=140, nullable=false) */
    protected $cidade;
    
    /** @ORM\Column(type="string", length=140, nullable=false) */
    protected $bairro;
    
    /** @ORM\Column(type="integer", length=8, nullable=false) */
    protected $cep;
    
    /**
     * @ORM\ManyToOne(targetEntity="Uf")
     * @ORM\JoinColumn(name="uf", referencedColumnName="id")
     */
    protected $uf;
    
    /** @ORM\Column(type="integer", length=6) */
    protected $numero;
    
    /** @ORM\Column(type="string", length=140) */
    protected $complemento;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    function getId() {
        return $this->id;
    }

    function getRua() {
        return $this->rua;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCep() {
        return $this->cep;
    }

    function getUf() {
        return $this->uf;
    }

    function getNumero() {
        return $this->numero;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRua($rua) {
        $this->rua = $rua;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setUf($uf) {
        $this->uf = $uf;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }


}
