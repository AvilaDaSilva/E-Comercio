<?php

namespace Produtos\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Produtos\Model\Categoria;
use DateTime;

class CategoriasService extends Service
{
    public function saveCategoria($dados)
    {
        try {
            $categoria = new Categoria();
            $categoria->setDescCategoria($dados['descricao']);
            $this->getObjectManager()->persist($categoria);

            $this->getObjectManager()->flush();
        } catch (Exception $ex) {
            throw new EntityException("Erro ao cadastrar a Categoria!");
        }
    }
    
    public function deleteCategoria($id)
    {
        $categoria = $this->getObjectManager()->getRepository('Produtos\Model\Categoria')
            ->findOneById($id);
        
        if ($categoria) {
            
            try {                
                $this->getObjectManager()->remove($categoria);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Categoria!');
            }
        } else {
            throw new EntityException ('Categoria não encontrada!');
        }
        
        return true;
    }
    
    public function updateCategoria($id, $dados)
    {
        $categoria = $this->getObjectManager()->getRepository('Produtos\Model\Categoria')
            ->findOneById($id);
        
        if ($categoria) {
                
            try {
                $categoria->setDescCategoria($dados['descricao']);
                $this->getObjectManager()->persist($categoria);
            
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Categoria');
            }
        } else {
            throw new EntityException('Categoria não encontrada!');
            
            return false;
        }
    }
}