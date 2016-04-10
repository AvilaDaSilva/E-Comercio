<?php

namespace Auth\Model;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\Validator\GreaterThan as GreaterThan;

/**
 * Entidade Controlador
 * 
 * @category Auth
 * @package Model
 * @author  Jean Cesar Detoni <jean_cd@unochapeco.edu.br>
 */
/**
 * @ORM\Entity @ORM\Table(name = "public.dm_usuario_perfil")
 */
class UsuarioPerfil {
    

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type = "integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="perfil", cascade={"persist"}) 
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    protected $id_usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="Perfil") 
     * @ORM\JoinColumn(name="id_perfil", referencedColumnName="id")
     */
    protected $id_perfil;

    protected $inputFilter;
    
    
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
            $validator = new GreaterThan(array('min' => 0)); 
            $validator->setMessage('O campo usuário não pode estar vazio');

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'Int'),
                        ),
            )));
            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_usuario',
                        'required' => true,
                    
                         'validators' => array($validator),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'id_perfil',
                        'required' => true,
                        'validators' => array(
                            array(
                                'name' => 'NotEmpty',
                                'options' => array('message' => 'O campo perfil não pode estar vazio')
                            )
                        ),
            )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}

?>