<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use DateTime;

class PessoaService extends Service
{
    public function savePessoa($dados)
    {
    try {
        $endereco = $this->getService('Auth\Service\Endereco')->saveEndereco($dados['endereco']); 
        $pessoa = new Pessoa();
        $pessoa->serNome($dados['pessoa']['nome']);
        $pessoa->setDataNascimento(
            new DateTime($dados['pessoa']['data_nascimento'])
        );
        $pessoa->setSexo($dados['pessoa']['sexo']);
        $pessoa->setEndereco($endereco);
        $this->getObjectManager()->persist($pessoa);
        
        $this->getService('Auth\Service\Usuario')->saveUsuario($dados['usuario'], $pessoa);
        
        $this->getObjectManager()->flush();
    } catch (Exception $ex) {
        throw new EntityException("Erro ao cadastrar o Pessoa!");
    }
    
        return $pessoa;
    }
    
    public function deletePessoa($id)
    {
        $pessoa = $this->getObjectMandager()->getRepository('Auth\Model\Pessoa')
            ->findOneById($id);
        
        if ($pessoa) {
            
            try {
                $this->getService('Auth\Model\Endereco')->deleteUsuario($id);
                
//                $this->getService('Auth\Service\Favorito')->deleteAllFavoritosByPessoa($id);
//                $this->getService('Auth\Service\Reserva')->deleteAllReservasByPessoa($id);
//                $this->getService('Auth\Service\Avalhacao')->deleteAllAvalhacoesByPessoa($id);
//                $this->getService('Auth\Service\Carrinho')->deleteAllCarrinhosByPessoa($id);
//                $this->getService('Auth\Service\Compra')->deleteAllComprasByPessoa($id);
                $this->getObjectManager()->remove($pessoa);
                $this->getService('Auth\Service\Endereco')->deleteEndereco(
                        $pessoa->getEndereco()->getId()
                );
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Pessoa!');
            }
        } else {
            throw new EntityException ('Pessoa não encontrada!');
        }
        
        return true;
    }
    
    public function updatePessoa($id, $dados)
    {
        $pessoa = $this->getObjectMandager()->getRepository('Auth\Model\Pessoa')
            ->findOneBy(["id" =>$id]);
        
        if ($pessoa) {
                
            try {
                $endereco = $this->getService('Auth\Service\Endereco')
                    ->updateEndereco($dados['endereco'],
                                    $pessoa->getEndereco()->getId());
                
                $this->getService('Auth\Service\Endereco')->updateUsuario($dados['usuario'], $id);
                
                $pessoa->serNome($dados['pessoa']['nome']);
                $pessoa->setDataNascimento(
                    new DateTime($dados['pessoa']['data_nascimento'])
                );
                $pessoa->setSexo($dados['pessoa']['sexo']);
                $pessoa->setEndereco($endereco);
                $this->getObjectManager()->persist($pessoa);
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Pessoa');
            }
        } else {
            throw new EntityException('Pessoa não encontrada!');
        }
        
        return $pessoa;
    }
}