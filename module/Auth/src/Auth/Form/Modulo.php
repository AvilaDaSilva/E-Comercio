<?php

namespace Auth\Form;

use Zend\Form\Form;

class Modulo extends Form {

    public function __construct() {
        parent::__construct('Modulo');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', BASE_URL . '/auth/modulos/save');
        $this->setAttribute('id', 'validar');

        $this->add(array(
            'type' => 'text',
            'name' => 'url_modulo',
            'options' => array(
                'label' => 'URL do modulo:*'
            ),
            'attributes' => array(
                'id' => 'url_modulo',
                'class' => 'form-control demo-oi-errinput'
        )));

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'desc_modulo',
            'type' => 'text',
            'options' => array(
                'label' => 'Modulo*'
            ),
            'attributes' => array(
                'id' => 'desc_modulo',
                'class' => 'form-control demo-oi-errinput'
            )
        ));

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'class' => 'btn btn-danger',
                'onclick' => "location.href='" . BASE_URL . "/auth/acao/index'",
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
    }

}

?>
