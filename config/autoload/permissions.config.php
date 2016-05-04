<?php

return array(
    'roles' => array(
        'gest',
        'cliente',
        'admin'
    ),
    'resources' => array(
        "Auth\Controller\Auth.login",
        "Auth\Controller\Auth.logout",
        "Auth\Controller\Usuarios.index",
        "Auth\Controller\Usuarios.save",
        "Auth\Controller\Usuarios.delete",
        "Auth\Controller\Auth.forbbiden",
    ),
    'permissions' => array(
        'gest' => array(
            "Auth\Controller\Auth.login",
            "Auth\Controller\Auth.forbbiden"
        ),
        'admin' => array(
            "Auth\Controller\Auth.login",
            "Auth\Controller\Auth.logout",
            "Auth\Controller\Usuarios.index",
            "Auth\Controller\Usuarios.save",
            "Auth\Controller\Usuarios.delete",
        ),
        'cliente' => array(
            "Auth\Controller\Auth.login",
            "Auth\Controller\Auth.logout",
            "Auth\Controller\Usuarios.index",
        ),
    )
);