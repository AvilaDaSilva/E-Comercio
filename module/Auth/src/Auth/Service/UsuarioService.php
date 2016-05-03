<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Auth\Model\Perfil;
use Auth\Model\Usuario;
use DateTime;

class UsuarioService extends Service
{
    public function saveUsuario($dados, $pessoa)
    {
        $perfil = $this->getObjectManager()->getRepository('Auth\Model\Perfil')
            ->findOneBy(['id' => Perfil::CLIENTE]);
        
        $ver_usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
            ->findOneBy(['email'=> $dados['email']]);
        
        if ($ver_usuario) {
            throw new EntityException("Já existe um Usuario cadastrado com esse e-mail.");
            
            return false;
        }

        try {
            $usuario = new Usuario();
            $usuario->setSenha(md5($dados['senha']));
            $usuario->setPessoa($pessoa);
            $usuario->setPerfil($perfil);
            $usuario->setEmail($dados['email']);
            $this->getObjectManager()->persist($usuario);

            $this->getObjectManager()->flush();
    } catch (Exception $ex) {
        throw new EntityException("Erro ao cadastrar o Usuario!");
    }
        return $usuario;
    }
    
    public function deleteUsuario($id)
    {
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
            ->findOneById($id);
        
        if (!$usuario)
            $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
                ->findOneBy(["pessoa" => $id]);
        
        if ($usuario) {
            
            try {                
                $this->getObjectManager()->remove($usuario);
                
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException ('Erro ao apagar Usuario!');
            }
        } else {
            throw new EntityException ('Usuario não encontrado!');
        }
        
        return true;
    }
    
    public function updateUsuario($dados, $id)
    {
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
            ->findOneById($id);
        
        if (!$usuario)
            $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')
                ->findOneBy(["pessoa" => $id]);
        
        if ($usuario) {
                
            try {
                $usuario->setSenha(md5($dados['senha']));
                $usuario->setPerfil($perfil);
                $usuario->setEmail($dados['email']);
                $this->getObjectManager()->persist($usuario);
            
                $this->getObjectManager()->flush();
            } catch (Exception $ex) {
                throw new EntityException('Erro ao editar Usuario');
            }
        } else {
            throw new EntityException('Usuario não encontrado!');
            
            return false;
        }
        
        return $usuario;
    }
}