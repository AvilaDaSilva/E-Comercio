<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use DateTime;

class EnderecoService extends Service
{
    public function saveEndereco($dados)
    {
        $uf = $this->getObjectManager()->getRepository('Auth\Model\Uf')
            ->findOne($dados['uf']);

        try {
            $endereco = new Endereço();
            $endereco->setRua($dados['rua']);
            $endereco->setCidade($dados['cidade']);
            $endereco->setBairro($dados['bairro']);
            $endereco->setCep($dados['cep']);
            $endereco->setNumero($dados['numero']);
            $endereco->setComplemento($dados['complemento']);
            $endereco->setUf($uf);
            $this->getObjectManager()->persist($endereco);

            $this->getObjectManager()->flush();
        } catch (Exception $ex) {
            throw new EntityException("Erro ao cadastrar o Endereco!");
        }
            return $endereco;
    }
    
    public function deleteEndereco($id)
    {
        $endereco = $this->getObjectMandager()->getRepository('Auth\Model\Endereco')
            ->findOneById($id);
        
        if ($endereco) {
            
            try {
                $this->getObjectManager()->remove($endereco);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Endereco!');
            }
        } else {
            throw new EntityException ('Endereco não encontrado!');
        }
        
        return true;
    }
    
    public function updateEndereco($dados, $id)
    {
        $endereco = $this->getObjectMandager()->getRepository('Auth\Model\Endereco')
            ->findOneBy(["id" =>$id]);
        
        $uf = $this->getObjectManager()->getRepository('Auth\Model\Uf')
            ->findOneBy(["id" => $dados['uf']]);
        
        if ($endereco) {
                
            try {
                $endereco = new Endereço();
                $endereco->setRua($dados['rua']);
                $endereco->setCidade($dados['cidade']);
                $endereco->setBairro($dados['bairro']);
                $endereco->setCep($dados['cep']);
                $endereco->setNumero($dados['numero']);
                $endereco->setComplemento($dados['complemento']);
                $endereco->setUf($uf);
                $this->getObjectManager()->persist($endereco);

                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Endereco');
            }
        } else {
            throw new EntityException('Endereco não encontrado!');
        }
        
        return $endereco;
    }
}