<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Empresa
 * 
 * @category Auth
 * @package Service
 * @author Cezar Junior Souza <cezar08@unochapeco.edu.br>   
 */
class Empresa extends Service {

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param string $search Filtro para busca empresas, não obrigatório
     * @return array Array contendo: Empresa.id, Empresa.nome_empresa
     */
    public function getEmpresas() {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Empresa.id', 'Empresa.nome_empresa')
                ->from('\Auth\Model\Empresa', 'Empresa')
                ->orderBy('Empresa.nome_empresa', 'ASC');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Empresa no banco de dados
     * @return \Auth\Model\Empresa 
     */
    public function saveEmpresa($data) {
        if ($data['id'] != '')
            $empresa = $this->getObjectManager()->find('\Auth\Model\Empresa', $data['id']);
        else
            $empresa = new \Auth\Model\Empresa();
        $filters = $empresa->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid())
            throw new EntityException('Dados inválidos');
        $data = $filters->getValues();
        $empresa->nome_empresa = $data['nome'];
        $this->getObjectManager()->persist($empresa);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao cadastrar Empresa, tente novamente mais tarde');
        }
        return $empresa;
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>  
     * @param integer $id Identificador da empresa
     * @return void
     */
    public function deleteEmpresa($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar uma empresa');
        $empresa = $this->getObjectManager()->find('\Auth\Model\Empresa', $id);
        if (!$empresa)
            throw new EntityException('Empresa não encontrada');
        $this->getObjectManager()->remove($empresa);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não possui vínculos');
        }
    }
    
    /**
     * @author Willian Gustavo Mendo <williammendo@unochapeco.edu.br>     
     * @param int $id identificador de usuario para filtrar emrpesa
     * @return array Array contendo: Empresa.id, Empresa.nome_empresa
     */
    
    public function getEmpresasPUsuario($id){
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Empresa.id', 'Empresa.nome_empresa')
                ->from('Auth\Model\UsuarioPerfil', 'UPerfil')
                ->join('UPerfil.id_usuario', 'Usuario')
                ->join('UPerfil.id_perfil', 'Perfil')
                ->join('Perfil.id_filial', 'Filial')
                ->join('Filial.id_empresa', 'Empresa')
                ->orderBy('Empresa.nome_empresa')
                ->distinct('Empresa.nome_empresa')
                ->where('Usuario.id = ?1')
                ->setParameter(1, $id);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

}
