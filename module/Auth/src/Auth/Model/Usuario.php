<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity @ORM\Table(name = "public.dm_usuario")
 */
class Usuario {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type = "integer")
     */
    protected $id;

    /**
     * @ORM\Column(type = "string")
     */
    protected $nome;
    
    /**
     * @ORM\Column(type = "string", unique=true)
     */
    protected $login;
    
    /**
     * @ORM\ManyToOne(targetEntity="StatusUsuario") 
     * @ORM\JoinColumn(name="ativo", referencedColumnName="id")
     */
    protected $ativo;

    /**
     * @ORM\Column(type = "datetime")
     */
    protected $data_ativacao;

    /**
     * @ORM\Column(type = "datetime", nullable=true)
     */
    protected $data_desativacao;

    /**
     * @ORM\Column(type = "string")
     */
    protected $email;

    /**
     * @ORM\Column(type = "string")
     */
    protected $senha;

    /**
     * @ORM\Column(type = "string")
     */
    protected $hash_nova_senha;

    /**
     * @ORM\Column( type="datetime", nullable=true)
     */
    protected $data_utl_solicitacao_alteracao;

    /**
     * @ORM\OneToOne(targetEntity="Programa")
     * @ORM\JoinColumn(name="programa", referencedColumnName="id")
     */
    protected $programa;
    protected $inputFilter;

    public function __construct() {
        $this->perfil = new ArrayCollection();
    }

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'nome',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToUpper',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 80,
                                    'message' => 'O campo Nome deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Nome não pode estar vazio')
                            )
                        ),
            )));
            
            $inputFilter->add($factory->createInput(array(
                        'name' => 'login',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 5,
                                    'max' => 20,
                                    'message' => 'O campo Login deve ter mais que 5 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Login não pode estar vazio')
                            )
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'ativo',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'data_ativacao',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'data_desativacao',
                        'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'email',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                            array('name' => 'StringToLower',
                                'options' => array('encoding' => 'UTF-8')
                            ),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 3,
                                    'max' => 80,
                                    'message' => 'O campo E-mail deve ter mais que 3 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo E-mail não pode estar vazio')
                            ),
                            array(
                                'name' => 'EmailAddress',
                                'options' => array('message' => 'O campo E-mail deve conter um e-mail válido')
                            )
                        ),
            )));


            $inputFilter->add($factory->createInput(array(
                        'name' => 'senha',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 6,
                                    'max' => 80,
                                    'message' => 'O campo Senha deve ter mais que 6 caracteres e menos que 80',
                                ),
                            ),
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo Senha não pode estar vazio')
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'perfis',
                        'required' => false,
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}

?>
