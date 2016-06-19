<?php

namespace Produtos\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Produtos\Model\Promocao;
use DateTime;

class PromocoesService extends Service
{
    public function savePromocao($dados)
    {
        try {
            $promocao = new Promocao();
            $promocao->setDataFinal(new \DateTime($dados['data_final']));
            $promocao->setDataInicial(new \DateTime($dados['data_inicial']));
            $promocao->setDescPromocao($dados['desc_promocao']);
            $promocao->setDesconto($dados['desconto']);
            
            if ($dados['status_promocao'] == 1)
                $promocao->setStatusPromocao(true);
            else
                $promocao->setStatusPromocao(false);
            
            foreach ($dados['produtos'] as $produto) {
                $produto_v = $this->getObjectManager()->getRepository('Produtos\Model\Produto')
                    ->findOneById($produto);
                
                if ($produto_v) {
                    $produto_v->getPromocoes()->add($promocao);
                    $promocao->getProdutos()->add($produto_v);
                }
            }
            
            $this->getObjectManager()->persist($promocao);

            $this->getObjectManager()->flush();
        } catch (Exception $ex) {
            throw new EntityException("Erro ao cadastrar a Promocao!");
        }
    }
    
    public function deletePromocao($id)
    {
        $promocao = $this->getObjectManager()->getRepository('Produtos\Model\Promocao')
            ->findOneById($id);
        
        if ($promocao) {
            
            try {                
                
                foreach ($promocao->getProdutos() as $produto) {
                    $produto->getPromocoes()->removeElementp($promocao);
                    $promocao->getProdutos()->removeElement($produto);
                }
                
                $this->getObjectManager()->remove($promocao);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Promocao!');
            }
        } else {
            throw new EntityException ('Promocao não encontrada!');
        }
        
        return true;
    }
    
    public function updatePromocao($id, $dados)
    {
        $promocao = $this->getObjectManager()->getRepository('Produtos\Model\Promocao')
            ->findOneById($id);
        
        if ($promocao) {
                
            try {
                
                $promocao->setDataFinal(new \DateTime($dados['data_final']));
                $promocao->setDataInicial(new \DateTime($dados['data_inicial']));
                $promocao->setDescPromocao($dados['desc_promocao']);
                $promocao->setDesconto($dados['desconto']);

                if ($dados['status_promocao'] == 1)
                    $promocao->setStatusPromocao(true);
                else
                    $promocao->setStatusPromocao(false);
                
                foreach ($promocao->getProdutos() as $produto) {
                    $promocao->getProdutos()->removeElement($produto);
                }
                
                foreach ($dados['produtos'] as $produto) {
                    $produto_v = $this->getObjectManager()->getRepository('Produtos\Model\Produto')
                        ->findOneById($produto);
                    
                    if ($produto_v) {
                        $produto_v->getPromocoes()->add($promocao);
                        $promocao->getProdutos()->add($produto_v);
                    }
                }
                $this->getObjectManager()->persist($promocao);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Promocao');
            }
        } else {
            throw new EntityException('Promocao não encontrada!');
            
            return false;
        }
    }
}