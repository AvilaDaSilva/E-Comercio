<?php

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

/**
 * Controlador Pessoas
 * @data 20-10/2014
 * @category Auth
 * @package Controller 
 * @author  Willian Gustavo Mendo <willianmendo@unochapecio.edu>
 */
class Usuario extends Form {
	public $checkbox, $checkboxLabel;
    public function __construct($perfis) {
        parent::__construct('Usuario');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL.'/auth/usuarios/save');
		$this->setAttribute('id', 'validar');
		$this->add(array(
			'name' => 'id',
			'type' => 'hidden',
		));
		$status = array(
			'name'=> 'ativo',
			'type'=> 'select',
			'options' => array(
				'class' => 'control-label',
				'label' => 'Selecione um status* ',
				'value_options' => array('1' => 'ATIVO', '2' => 'INATIVO'),
			),
			'attributes' => array (
				'id'=> 'ativo',
				'class' => 'chosen-select label label-info form-control',
				'title' => 'Status',
			)
		);      
		$nome = array(
			'type' => 'text',
			'name' => 'nome',
			'options' => array('label' => 'Nome*', 'class' => 'control-label'),
			'attributes' => array('size' => '80',
				'class' => 'form-control',
				'title' => 'Nome Usuário',
				'placeholder' => 'Nome',
				'data-error-message' => 'Campo inválido!')
		);
                
                $login = array(
			'type' => 'text',
			'name' => 'login',
			'options' => array('label' => 'Login*', 'class' => 'control-label'),
			'attributes' => array('size' => '20',
				'class' => 'form-control',
				'title' => 'Login',
				'placeholder' => 'Login',
				'data-error-message' => 'Campo inválido!')
		);
                
		$data_ativacao = array(
			'type' => 'hidden',
			'name' => 'data_ativacao',
		);
		$data_desativacao = array(
			'type' => 'hidden',
			'name' => 'data_desativacao',
		);
		$email = array(
			'type' => 'text',
			'name' => 'email',
			'attributes' => array('title' => 'E-mail', 'class' => 'form-control', 'placeholder' => 'e-mail'),
			'options' => array('label' => 'E-mail* '),
        );
		$senha = array(
			'type' => 'password',
			'name' => 'senha',
			'options' => array('label' => 'Senha* '),
			'attributes' => array(
				'class' => 'form-control',
				'title' => 'A senha deve ter mais de 6 caracteres!',
				'placeholder' => 'senha'
			),
		);
        //$this->checkbox = $perfis;
        //$this->checkbox += $perfis;
        //$this->checkboxLabel = 'Perfis:* ';
        /*$this->add(array(
        		'type' => 'text',
        		'name' => 'perfis',
        		'attributes' => array(
        				'value' =>  $perfis, 
        		)
        ));*/
        foreach($perfis as $p){
            $tempVar[$p['id']] = $p['desc_perfil'];
        }
        $this->checkbox[] = array(
        		'type' => 'checkbox',
        		'name' => 'perfis[]',
        		'id' => 'perfis[]',
        		'label' => 'Perfis:* ',
        		'values' => $tempVar,
        );
/*        $this->checkbox[] = array(
        		'type' => 'checkbox',
        		'name' => 'perfis',
        		'id' => 'perfis[]',
        		'label' => 'Perfis:* ',
        		'values' => $tempVar,
        );*/
/*        foreach($perfis as $p){
            $this->checkbox[$p['id']] = 'AA'.$p['desc_perfil'];
        }
        
/*        $this->add(array(
            'type' => 'MultiCheckbox',
            'name' => 'perfis',
            'options' => array(
                'label' => 'Perfis:* ',
                'value_options' => $selectPerfis
            ),
            'attributes' => array(
                'id' => 'perfis[]',
            	'class' => '',
        )));
*/

       /* $botao = new Element\Submit('botao');
        $botao->setValue('Salvar')->setAttribute('class', 'btn submit')
                ->setAttributes(array('title' => 'Salvar'));
        */
        $cancel = new Element\Button('cancelar');
        $cancel->setValue('Cancelar')
                ->setAttributes(array('class' => 'btn btn-danger', 'onclick' => "location.href='".BASE_URL."/auth/usuarios/index'"))
                ->setLabel('Cancelar')
                ->setAttributes(array('title' => 'Cancelar'));
        $this->add($nome);
        $this->add($email);
        $this->add($senha);     
        $this->add($data_desativacao);
        $this->add($data_ativacao);     
        $this->add($status);  
        //$this->add($botao);
        $this->add($cancel);
        $this->add($login);
    }

}
?>