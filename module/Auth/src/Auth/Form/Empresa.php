<?php

/*
 * Form Empresa
 * @author Rodrigo Angelo Valentini <rodrigoangelo@unochapeco.edu.br>
 * @author Huilson José Lorenzi <huilson@unochapeco.edu.br>
 * 
 */

namespace Auth\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class Empresa extends Form {

    public function __construct() {
        parent::__construct('Empresa');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL . '/auth/empresas/save');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Nome da empresa*',
                'class' => 'control-label'
            ),
            'attributes' => array(
                'title' => 'Nome da empresa',
                'class' => 'form-control demo-oi-errinput',
                'id' => 'nome',
                'data-error-message' => 'Campo inválido!',
        )));
        $cancel = new Element\Button('cancelar');
        $cancel->setValue('Cancelar')
                ->setAttributes(array('class' => 'btn btn-danger', 'onclick' => "location.href='" . BASE_URL . "/auth/usuarios/index/page/1'"))
                ->setLabel('Cancelar')
                ->setAttributes(array('title' => 'Cancelar'));


        $this->add($cancel);
    }

}

?>
