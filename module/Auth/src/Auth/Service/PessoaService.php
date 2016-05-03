<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Auth\Model\Pessoa;
use DateTime;

class PessoaService extends Service
{
    public function savePessoa($dados)
    {
        $dados_u = $dados['usuario'];    
        $dados_p = $dados_u['pessoa'];    
        $dados_e = $dados_p['endereco'];    

        try {
            $endereco = $this->getService('Auth\Service\Endereco')
                ->saveEndereco($dados_e); 
            $pessoa = new Pessoa();
            $pessoa->setNome($dados_p['nome']);
            $pessoa->setDataNascimento(
                new DateTime($dados_p['data_nascimento'])
            );
            $pessoa->setSexo($dados_p['sexo']);
            $pessoa->setEndereco($endereco);
            $this->getObjectManager()->persist($pessoa);

            $this->getService('Auth\Service\Usuario')
                ->saveUsuario($dados_u, $pessoa);

            $this->getObjectManager()->flush();
        } catch (Exception $ex) {
            throw new EntityException("Erro ao cadastrar o Pessoa!");
        }
    
        return $pessoa;
    }
    
    public function deletePessoa($id)
    {
        $pessoa = $this->getObjectManager()->getRepository('Auth\Model\Pessoa')
            ->findOneById($id);
        
        if ($pessoa) {
            $endereco_id = $pessoa->getEndereco()->getId();
            
            try {
                $this->getService('Auth\Service\Usuario')->deleteUsuario($id);
                
//                $this->getService('Auth\Service\Favorito')->deleteAllFavoritosByPessoa($id);
//                $this->getService('Auth\Service\Reserva')->deleteAllReservasByPessoa($id);
//                $this->getService('Auth\Service\Avalhacao')->deleteAllAvalhacoesByPessoa($id);
//                $this->getService('Auth\Service\Carrinho')->deleteAllCarrinhosByPessoa($id);
//                $this->getService('Auth\Service\Compra')->deleteAllComprasByPessoa($id);                          
                $this->getObjectManager()->remove($pessoa);
                $this->getService('Auth\Service\Endereco')->deleteEndereco(
                        $endereco_id
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
        $dados_u = $dados['usuario'];    
        $dados_p = $dados_u['pessoa'];    
        $dados_e = $dados_p['endereco'];
        
        $pessoa = $this->getObjectManager()->getRepository('Auth\Model\Pessoa')
            ->findOneBy(["id" =>$id]);
        
        if ($pessoa) {
                
            try {
                $endereco = $this->getService('Auth\Service\Endereco')
                    ->updateEndereco($dados_e,
                                    $pessoa->getEndereco()->getId());
                
                $this->getService('Auth\Service\Usuario')->updateUsuario($dados_u, $id);
                
                $pessoa->setNome($dados_p['nome']);
                $pessoa->setDataNascimento(
                    new DateTime($dados_p['data_nascimento'])
                );
                $pessoa->setSexo($dados_p['sexo']);
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