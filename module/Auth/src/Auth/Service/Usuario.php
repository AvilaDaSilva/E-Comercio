<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade usuario
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */
Class Usuario extends Service {

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param string $search Filtro para busca Usuarios, não obrigatório
     * @return array Array contendo: 'Usuario.id Usuario.nome Usuario.email Autenticacao.desc_tipo_autenticacao Status.desc_status_usuario Usuario.data_ativacao Usuario.data_desativacao
     */
    public function getUsuarios($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Usuario.id', 'Usuario.nome', 'Usuario.login','Usuario.email', 'Status.desc_status_usuario', 'Usuario.data_ativacao', 'Usuario.data_desativacao')
                ->from('\Auth\Model\Usuario', 'Usuario')
                ->join('Usuario.ativo', 'Status')
                ->orderBy('Usuario.nome', 'ASC')
                ->where("Usuario.nome LIKE ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Usuario no banco de dados
     * @return \Auth\Model\Usuario
     */
    public function saveUsuario($data) {
        if ($data['id'] != '') {
            $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $data['id']);
            $flag = 1;
        } else {
            $usuario = new \Auth\Model\Usuario();
            $usuario->data_ativacao = new \DateTime('now');
            $flag = 0;
        }
        if ($flag == 0) {
            $select = $this->getObjectManager()
                    ->createQueryBuilder()
                    ->select('Usuario.id')
                    ->from('\Auth\Model\Usuario', 'Usuario')
                    ->where("Usuario.login LIKE ?1")
                    ->setParameter(1, $data['login']);
            $query = $select->getQuery();
            $result = $query->getResult();
            if ($result) {
                throw new EntityException('Login já cadastrado');
            }
        }
        $filters = $usuario->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid()) {
            throw new EntityException('Dados inválidos');
        }
        $data = $filters->getValues();
        $usuario->nome = $data['nome'];
        $usuario->login = $data['login'];
        $usuario->email = $data['email'];
        $usuario->senha = md5($data['senha']);
        $ativo = $this->getObjectManager()->find('\Auth\Model\StatusUsuario', $data['ativo']);
        if (!$ativo)
            throw new EntityException('Status não encontrado');
        $usuario->ativo = $ativo;
        if ($data['ativo'] == 2) {
            $data['data_desativacao'] = new \DateTime('now');
            $usuario->data_desativacao = $data['data_desativacao'];
        }

        $this->getObjectManager()->persist($usuario);
        $up = $this->getObjectManager()->getRepository('Auth\Model\UsuarioPerfil')->findBy(array('id_usuario' => $usuario->id));
        if ($up) {
            foreach ($up as $u) {
                $this->getObjectManager()->remove($u);
            }
        }
        if (isset($data['perfis'])) {
            foreach ($data['perfis'] as $p) {
                $perfil = $this->getObjectManager()->find('Auth\Model\perfil', $p);
                if (!$perfil)
                    throw new EntityException('Perfil não encontrado');
                $up = new \Auth\Model\UsuarioPerfil();
                $up->id_usuario = $usuario;
                $up->id_perfil = $perfil;
                $this->getObjectManager()->persist($up);
            }
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao salvar usuário, tente novamente mais tarde');
        }
        return $usuario;
    }

    /** Serviço para salvar um supervisor como usuário ativo, ao solicitar uma nova vaga
     * @author Joao Krzyzaniak <joaovicente@unochapeco.edu.br>
     * @param array $data Dados para salvar um usuário
     * @return void
     */
    public function saveUsuarioSupervisor($data) {
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('email' => $data['email']));
        if (!$usuario) {
            $usuario = new \Auth\Model\Usuario();
            $autenticacao = $this->getObjectManager()->find('Auth\Model\Autenticacao', 2);
            $ativo = $this->getObjectManager()->find('\Auth\Model\StatusUsuario', 1);
            $usuario->setNome($data['nome']);
            $usuario->setEmail($data['email']);
            $usuario->setData_ativacao(new \DateTime('now'));
            $usuario->setSenha(md5(uniqid()));
            $usuario->setAtivo($ativo);
            $usuario->setId_tipo_autenticacao($autenticacao);
            try {
                $this->getObjectManager()->flush();
            } catch (\Exception $e) {
                throw new EntityException("Erro ao cadastrar supervisor como usuário");
            }
        }
        $this->getService('Auth\Service\UsuarioPerfil')->vinculaUsuarioSupervisor($usuario);
    }

    /**
     * @author Willian Gustavp Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id Identificador de Usuario
     * @return void
     */
    public function deleteUsuario($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar um usuário');
        $usuario = $this->getObjectManager()->find('\Auth\Model\Usuario', $id);
        if (!$usuario)
            throw new EntityException('Usuário não encontrado');
        $usuarioPerfil = $this->getObjectManager()->getRepository('Auth\Model\UsuarioPerfil')->findBy(array('id_usuario' => $usuario->id));
        foreach ($usuarioPerfil as $up) {
            $this->getObjectManager()->remove($up);
        }
        $log = $this->getObjectManager()->getRepository('Auth\Model\Log')->findBy(array('usuario' => $usuario->id));
        foreach ($log as $l) {
            $this->getObjectmanager()->remove($l);
        }
        $this->getObjectManager()->remove($usuario);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não possui vínculos');
        }
    }

}
