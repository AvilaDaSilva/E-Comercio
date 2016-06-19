<?php

namespace Produtos\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Produtos\Model\Produto;
use Produtos\Model\Estoque;
use DateTime;

class ProdutosService extends Service
{
    public function saveProduto($dados)
    {
        $categoria = $this->getObjectManager()->getRepository('Produtos\Model\Categoria')
            ->findOneById($dados['categoria']);
        if (!$categoria)
            throw new EntityException("Categoria não encontrada");
        try {
            $imagem = file_get_contents($dados['imagem']['tmp_name']);
            $enc_imagem = base64_encode($imagem);
            $estoque = new Estoque();
            $produto = new Produto();
            $produto->setCategoria($categoria);
            $produto->setDescProduto($dados['desc_produto']);
            $produto->setDetalhes($dados['detalhes']);
            $produto->setValor($dados['valor']);
            $produto->setAvalhacao('0');
            $produto->setImagem($enc_imagem);
            
            $this->getObjectManager()->persist($produto);

            $this->getObjectManager()->flush();
            
            $estoque->setProduto($produto);
            $estoque->setQuantidade(0);
            
            $this->getObjectManager()->persist($estoque);

            $this->getObjectManager()->flush();
        } catch (Exception $ex) {
            throw new EntityException("Erro ao cadastrar o Produto!");
        }
    }
    
    public function deleteProduto($id)
    {
        $produto = $this->getObjectManager()->getRepository('Produtos\Model\Produto')
            ->findOneById($id);
        
        if ($produto) {
            
            $estoque = $this->getObjectManager()->getRepository('Produtos\Model\Estoque')
            ->findOneBy(['produto' => $produto->getId()]);
            
            try {                
                $this->getObjectManager()->remove($estoque);
                $this->getObjectManager()->remove($produto);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Produto!');
            }
        } else {
            throw new EntityException ('Produto não encontrado!');
        }
        
        return true;
    }
    
    public function updateProduto($id, $dados)
    {
        $produto = $this->getObjectManager()->getRepository('Produtos\Model\Produto')
            ->findOneById($id);
        
        if ($produto) {
                
            try {
                $produto->setCategoria($categoria);
                $produto->setDescProduto($dados['desc_produto']);
                $produto->setDetalhes($dados['detalhes']);
                $produto->setValor($dados['valor']);
                $this->getObjectManager()->persist($produto);
            
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Produto');
            }
        } else {
            throw new EntityException('Produto não encontrado!');
            
            return false;
        }
    }
}