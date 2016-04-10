<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'ecommerce',
                )
            ),
        ),
        'entitymanager' => array(
            'orm_default' => array(
                'connection' => 'orm_default',
                'configuration' => 'orm_default'
            ),
        ),
    ),
);
?>

