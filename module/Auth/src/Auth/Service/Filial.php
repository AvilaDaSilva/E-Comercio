<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Filial
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo willianmendo@unochapeco.edu.br
 */
class Filial extends Service {

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param string $search Filtro para busca Filiais, não obrigatório
     * @return array Array contendo: Filial.id, Filial.nome_filial, Filial.desc_modulo_filial, Empresa.nome_empresa
     */
    public function getFiliais() {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Filial.id', 'Filial.nome_filial', 'Filial.desc_modulo_filial', 'Empresa.nome_empresa')
                ->from('\Auth\Model\Filial', 'Filial')
                ->join('Filial.id_empresa', 'Empresa')
                ->orderBy('Filial.nome_filial', 'ASC');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Filial no banco de dados
     * @param string $imgName Nome do arquivo que contém a imagem destinada a filial
     * @return \Auth\Model\Filial 
     */
    public function saveFiliais($data, $imgName) {
        if ($data['id'] != '') {
            $filial = $this->getObjectManager()->find('\Auth\Model\Filial', $data['id']);
        } else {
            $filial = new \Auth\Model\Filial();
        }
        $filters = $filial->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid())
            throw new EntityException('Dados inválidos');
        $data = $filters->getValues();
        $data['url_img_filial'] = "/baners/" . $imgName;
        $filial->nome_filial = $data['nome_filial'];
        $filial->desc_modulo_filial = $data['desc_modulo_filial'];
        $filial->desc_exibi_filial = $data['desc_exibi_filial'];
        $filial->url_img_filial = $data['url_img_filial'];
        $empresa = $this->getObjectManager()->find('\Auth\Model\Empresa', $data['id_empresa']);
        if (!$empresa)
            throw new EntityException('Empresa não encontrada');
        $filial->id_empresa = $empresa;
        $this->getObjectManager()->persist($filial);
        try {
            $this->getObjectManager()->flush();
        } catch (exception $e) {
            echo $e;
            throw new EntityException('Tente novamente mais tarde');
        }
        return $filial;
    }

    /**
     * @author Willian Gustavp Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id Identificador da Filial
     * @return void
     */
    public function deleteFilial($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar uma filial');
        $filial = $this->getObjectManager()->find('\Auth\Model\Filial', $id);
        if (!$filial)
            throw new EntityException('Filial não encontrada');
        $this->getObjectManager()->remove($filial);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não possui vínculos');
        }
    }

    public function getFilialPUsuario($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Filial.id', 'Filial.nome_filial', 'Filial.desc_exibi_filial')
                ->from('Auth\Model\UsuarioPerfil', 'UsuarioP')
                ->join('UsuarioP.id_perfil', 'Perfil')
                ->join('UsuarioP.id_usuario', 'Usuario')
                ->join('Perfil.id_filial', 'Filial')
                ->orderBy('Filial.nome_filial', 'ASC')
                ->where('Usuario.id = ?1')
                ->setParameter(1, $id)
                ->distinct('Filial.nome_filial');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param integer $id identificador de empresa para filtrar as filiais
     * @return array Array contendo: Filial.id, Filial.nome_filial
     */
    public function getFiliaisPEmpresa($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Filial.id', 'Filial.nome_filial')
                ->from('Auth\Model\Filial', 'Filial')
                ->join('Filial.id_empresa', 'Empresa')
                ->orderBy('Filial.nome_filial', 'ASC')
                ->distinct('Filial.nome_filial')
                ->where('Empresa.id = ?1')
                ->setparameter(1, $id);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

}
