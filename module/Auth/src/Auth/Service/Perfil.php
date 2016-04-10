<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;
use Zend\Permissions\Acl\Acl as Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * Serviço para manipulação de dados da entidade Perfil
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */
class Perfil extends Service {

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>        
     * @param string $search Filtro para perfil, não obrigatório
     * @return array Array contendo: Perfil.id, Perfil.desc_perfil, Filial.nome_filial
     */
    public function getPerfis($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Perfil.id', 'Perfil.desc_perfil', 'Filial.nome_filial')
                ->from('Auth\Model\Perfil', 'Perfil')
                ->join('Perfil.id_filial', 'Filial')
                ->orderBy('Perfil.desc_perfil', 'ASC')
                 ->where("Perfil.desc_perfil like ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br> 
     * @param array $data Dados para armazenar um objeto Perfil no banco de dados
     * @return \Auth\Model\Perfil 
     */
    public function savePerfil($data) {
        if ($data['id'] != ""){ 
            $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $data['id']);
        }else{
            $perfil = new \Auth\Model\Perfil();
        }
        $filial = $this->getObjectmanager()->find('Auth\Model\Filial', $data['id_filial']);
        if(!$filial)
            throw new EntityException('Filial não encontrada');
        $filters = $perfil->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid())
            throw new EntityException('Dados inválidos');
        $data = $filters->getValues();
        $perfil->id_filial = $filial;
        $perfil->desc_perfil = $data['desc_perfil'];
        $this->getObjectManager()->persist($perfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
        return $perfil;
    }

    /**
     @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>    
     * @param integer $id Identificador do perfil
     * @return void
     */
    public function deletePerfil($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar um perfil');
        $perfil = $this->getObjectManager()->find('\Auth\Model\Perfil', $id);
        if (!$perfil)
            throw new EntityException('Perfil não encontrado');
        $this->getObjectManager()->remove($perfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não está sendo utilizado por outro programa');
        }
    }
    
    public function getPerfilPUsuario($usuario, $filial){
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Filial.id as id_filial' ,'Perfil.id', 'Perfil.desc_perfil')
                ->from('Auth\Model\UsuarioPerfil', 'UsuarioP')
                ->join('UsuarioP.id_usuario', 'Usuario')
                ->join('UsuarioP.id_perfil', 'Perfil')
                ->join('Perfil.id_filial', 'Filial')
                ->orderBy('Perfil.desc_perfil', 'ASC')
                ->where('Usuario.id = ?1 AND Filial.id = ?2')
                ->setParameter(1, $usuario)
                ->setParameter(2, $filial);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
     
    }
    
    public function getPerfisPEmpresa(){
        $session = $this->getService('session');
        $id = $session['empresa'][0]['id'];
        $select = $this->getObjectmanager()->createQueryBuilder()
                ->select('Perfil.id', 'Perfil.desc_perfil')
                ->from('Auth\Model\Perfil', 'Perfil')
                ->join('Perfil.id_filial', 'Filial')
                ->join('Filial.id_empresa', 'Empresa')
                ->orderBy('Perfil.desc_perfil')
                ->where('Empresa.id = ?1')
                ->setParameter(1, $id);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }


}
