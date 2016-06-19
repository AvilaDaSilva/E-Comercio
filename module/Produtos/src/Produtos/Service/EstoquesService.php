<?php

namespace Produtos\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Produtos\Model\Produto;
use Produtos\Model\Estoque;
use DateTime;

class EstoquesService extends Service
{
    public function updateEstoque($id, $dados)
    {
        $estoque = $this->getObjectManager()->getRepository('Produtos\Model\Estoque')
            ->findOneById($id);
        
        if ($estoque) {
                
            try {
                
                if ($dados['operacao'] == 'S') {
                    $estoque->somar($dados['quantidade_nova']);
                } else {
                    $estoque->subtrair($dados['quantidade_nova']);
                }
                
                $this->getObjectManager()->persist($estoque);
            
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Estoque');
            }
        } else {
            throw new EntityException('Estoque não encontrado!');
            
            return false;
        }
    }
}