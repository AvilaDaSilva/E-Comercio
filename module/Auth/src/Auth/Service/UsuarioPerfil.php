<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade UsuarioPerfil
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
 */
class UsuarioPerfil extends Service {

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param string $search Filtro para buscar usuarios, não obrigatório
     * @return array Array contendo: Acao.id, Acao.desc_acao, Acao.acao_url, Programa.desc_programa, Programa.controller_programa, Filial.nome_filial, Filial.desc_modulo_filial
     */
    public function getUsuariosPerfis($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('UsuarioPerfil.id', 'Usuario.nome', 'Perfil.desc_perfil', 'Usuario.id as id_usuario')
                ->from('\Auth\Model\UsuarioPerfil', 'UsuarioPerfil')
                ->join('UsuarioPerfil.id_usuario', 'Usuario')
                ->join('UsuarioPerfil.id_perfil', 'Perfil')
                ->orderBy('Usuario.nome', 'ASC')
                ->where("Usuario.nome like ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>        
     * @param array $data Dados para vincular um objeto Usuario a um objeto Perfil no banco de dados
     * @return \Auth\Model\UsuarioPerfil 
     */
    public function saveUsuarioPerfil($data) {
        $perfisUsuario = array();
        $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $data['id_usuario']);
        if (!$usuario)
            throw new EntityException('Usuário inválido');
        $perfisUsuario = $this->getObjectManager()->getRepository("\Auth\Model\UsuarioPerfil")->findBy(array("id_usuario" => $usuario));
        if ($perfisUsuario) {
            foreach ($perfisUsuario as $pUser)
                $this->getObjectManager()->remove($pUser);
        } else
            $perfisUsuario[0] = new \Auth\Model\UsuarioPerfil();
        $filters = $perfisUsuario[0]->getInputFilter();
        $filters->setData($data);
        foreach ($data['id_perfil'] as $usuarioP) {
            $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $usuarioP);
            if (!$perfil)
                throw new EntityException('Perfil inválido');
            $usuarioPerfil = new \Auth\Model\UsuarioPerfil();
            $usuarioPerfil->id_perfil = $perfil;
            $usuarioPerfil->id_usuario = $usuario;
            $this->getObjectManager()->persist($usuarioPerfil);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao vincular usuário, tente novamente mais tarde');
        }
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id Identificador do acao
     * @return void
     */
    public function deleteUsuarioPerfil($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar um vínculo');
        $usuarioPerfil = $this->getObjectManager()->find("\Auth\Model\UsuarioPerfil", $id);
        if (!$usuarioPerfil)
            throw new EntityException('Vínculo não encontrado');
        $this->getObjectManager()->remove($usuarioPerfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não está sendo utilizado por outro programa');
        }
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>
     * @param integer $id Identificador do usuário
     * @return array Array contendo: id_usuario', id_perfil, nome, desc_perfil
     */
    public function getPerfisPUsuario($id) {
        $usuarioPerfil = $this->getObjectManager()->createQueryBuilder()
                        ->select('Usuario.id as id_usuario', 'Usuario.nome', 'Perfil.desc_perfil', 'Perfil.id as id_perfil', 'UsuarioPerfil.id')
                        ->from('\Auth\Model\UsuarioPerfil', 'UsuarioPerfil')
                        ->join('UsuarioPerfil.id_usuario', 'Usuario')
                        ->join('UsuarioPerfil.id_perfil', 'Perfil')
                        ->where("Usuario.id = " . $id)
                        ->getQuery()->getResult();
        return $usuarioPerfil;
    }

    /**
     * Serviço para verificar Usuario Perfil, utilizado no salvarSolicitante
     * @author William Moterle <williammoterle@unochapeco.edu.br>
     * @return array Array contendo: usuarioPerfil.id
     * @param objeto $usuario
     * @param objeto $perfil
     */
    public function verificaUsuarioPerfil($usuario, $perfil) {
        $select = $this->getObjectManager()->createQueryBuilder();
        $select->select('UsuarioPerfil.id')
                ->from('Auth\Model\UsuarioPerfil', 'UsuarioPerfil')
                ->join('UsuarioPerfil.id_usuario', 'Usuario')
                ->join('UsuarioPerfil.id_perfil', 'Perfil')
                ->where("UsuarioPerfil.id_usuario = ?1 and UsuarioPerfil.id_perfil = ?2")
                ->setParameters(array(1 => $usuario, 2 => $perfil));
        $query = $select->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param array $values
     * @return void
     */
    public function vinculaAprovador($values) {
        $perfisUsuario = array();
        $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $values['id_usuario']);
        if (!$usuario)
            throw new EntityException('Usuario inválido');
        $perfisUsuario = $this->getObjectManager()->createQueryBuilder()
                ->select("UsuarioPerfil.id")
                ->from("\Auth\Model\UsuarioPerfil", "UsuarioPerfil")
                ->where("UsuarioPerfil.id_usuario = $values[id_usuario] and (UsuarioPerfil.id_perfil = 58 or UsuarioPerfil.id_perfil = 59 or UsuarioPerfil.id_perfil = 60 )");
        $perfisUsuario = $perfisUsuario->getQuery()->getResult();
        if ($perfisUsuario) {
            foreach ($perfisUsuario as $pUser) {
                $objetoUsuarioPerfil = $this->getObjectManager()->find('\Auth\Model\UsuarioPerfil', $pUser);
                $this->getObjectManager()->remove($objetoUsuarioPerfil);
            }
        }
        $perfisUsuario = new \Auth\Model\UsuarioPerfil();
        $filters = $perfisUsuario->getInputFilter();
        $filters->setData($values);
        foreach ($values['id_perfil'] as $usuarioP) {
            $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $usuarioP);
            if (!$perfil)
                throw new EntityException('Perfil inválido');
            $usuarioPerfil = new \Auth\Model\UsuarioPerfil();
            $usuarioPerfil->setId_perfil($perfil);
            $usuarioPerfil->setId_usuario($usuario);
            $this->getObjectManager()->persist($usuarioPerfil);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao vincular usuário, tente novamente mais tarde');
        }
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param array $values
     * @return void
     */
    public function vinculaSolicitante($values) {
        $usuario = $this->getObjectmanager()->find('\Auth\Model\Usuario', $values['id_usuario']);
        if (!$usuario)
            throw new EntityException('Usuario invalido');
        $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $values['id_perfil'][0]);
        if (!$perfil)
            throw new EntityException('Perfil inválido');
        $usuarioPerfil = new \Auth\Model\UsuarioPerfil();
        $usuarioPerfil->setId_perfil($perfil);
        $usuarioPerfil->setId_usuario($usuario);
        $this->getObjectManager()->persist($usuarioPerfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao vincular usuário, tente novamente mais tarde');
        }
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param string Search, não obrigatorio.
     * @return Usuario.nome, Usuario.email,UsuarioPerfil.id, Perfil.id as id_perfil
     */
    public function getUsuarioScm($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Usuario.id', 'Usuario.nome', 'Usuario.email', 'UsuarioPerfil.id as id_UPerfil', 'Perfil.id as id_perfil')
                ->from('Auth\Model\Usuario', 'Usuario')
                ->join('Usuario.perfil', 'UsuarioPerfil')
                ->join('UsuarioPerfil.id_perfil', 'Perfil')
                ->orderBy('Usuario.nome', 'ASC')
                ->where("Perfil.id = 70 and Usuario.nome LIKE ?1")
                ->setParameters(array(1 => '%' . $search . '%'));
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id.
     * @return void
     */
    public function vinculaUsuarioScm($id) {
        $usuarioPerfil = $this->getObjectmanager()->getRepository('Auth\Model\UsuarioPerfil')->findOneBy(array('id_usuario' => $id,
            'id_perfil' => $this->getService('Auth\Service\Perfil')->getPerfilScm()));
        if ($usuarioPerfil)
            return;
        $usuario = $this->getObjectManager()->find('Auth\model\Usuario', $id);
        if (!$usuario)
            throw new EntityException('Usuario Invalido');
        $perfil = $this->getObjectManager()->find('Auth\Model\Perfil', $this->getService('Auth\Service\Perfil')->getPerfilScm());
        if (!$perfil)
            throw new EntityException('Perfil não Encontrado');
        $usuarioPerfil = new \Auth\Model\UsuarioPerfil();
        $usuarioPerfil->setId_perfil($perfil);
        $usuarioPerfil->setId_usuario($usuario);
        $this->getObjectManager()->persist($usuarioPerfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao vincular usuário, tente novamente mais tarde');
        }
    }

    /** Serviço para vincular um supervisor ao perfil de Supervisor de estágio
     * @author Joao Krzyzaniak <joaovicente@unochapeco.edu.br>
     * @param \Auth\Model\Usuario $usuario 
     * @return void
     */
    public function vinculaUsuarioSupervisor($usuario) {
        $usuario_perfil = $this->getObjectManager()->getRepository('Auth\Model\UsuarioPerfil')->findOneBy(array('id_usuario' => $usuario->getId(), 'id_perfil' => $this->getService('Auth\Service\Perfil')->getPerfilSupervisorEstagio()));
        if (!$usuario_perfil) {
            $usuario_perfil = new \Auth\Model\UsuarioPerfil();
            $usuario_perfil->setId_perfil($this->getObjectManager()->find('Auth\Model\Perfil', $this->getService('Auth\Service\Perfil')->getPerfilSupervisorEstagio()));
            $usuario_perfil->setId_usuario($usuario);
            $this->getObjectManager()->persist($usuario_perfil);
            try {
                $this->getObjectManager()->flush();
            } catch (\Exception $e) {
                throw new EntityException("Erro ao vincular perfil de Supervisor de estágio");
            }
        }
    }

}
