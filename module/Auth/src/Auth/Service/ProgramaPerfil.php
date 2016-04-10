<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade ProgramaPerfil
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
 */
class ProgramaPerfil extends Service {

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param string $search Filtro para buscar programas vinculados a perfis, não obrigatório
     * @return array Array contendo: ProgramaPerfil.id Programa.desc_programa Perfil.desc_perfil
     */
    public function getProgramaPerfis($search = null) {
        $select = $this->getObjectManager()
                ->CreateQueryBuilder()
                ->select('ProgramaPerfil.id', 'Programa.desc_programa', 'Perfil.desc_perfil')
                ->from('\Auth\Model\ProgramaPerfil', 'ProgramaPerfil')
                ->join('ProgramaPerfil.id_programa', 'Programa')
                ->join('ProgramaPerfil.id_perfil', 'Perfil')
                ->orderBy('Programa.desc_programa', 'ASC')
                ->where("Programa.desc_programa like ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>    
     * @param array $data Dados para Vincular um objeto programa a um objeto perfil no banco de dados
     * @return \Auth\Model\Programaperfil 
     */
    public function saveProgramaPerfil($data) {
        $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $data['id_programa']);
        if (!$programa)
            throw new EntityException('Programa inválido');
        $programaPerfil = $this->getObjectManager()->getRepository("\Auth\Model\ProgramaPerfil")->findBy(array("id_programa" => $programa));
        if ($programaPerfil) {
          foreach($programaPerfil as $pPerfil){
              $this->getObjectManager()->remove($pPerfil);
          }
        } else
            $programaPerfil[0] = new \Auth\Model\ProgramaPerfil();
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
        $filters = $programaPerfil[0]->getInputFilter();
        $filters->setData($data);
        if ($data['id_perfil']) {
            foreach ($data['id_perfil'] as $p) {
                $perfil = $this->getObjectManager()->find('Auth\Model\Perfil', $p);
                if (!$perfil)
                    throw new EntityException('Perfil invalido');
                $pPerfil = new \Auth\Model\ProgramaPerfil();
                $pPerfil->id_perfil = $perfil;
                $pPerfil->id_programa = $programa;
                $this->getObjectManager()->persist($pPerfil);
            }
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
        return $programaPerfil;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>    
     * @param integer $id Identificador do ProgramaPerfil
     * @return void
     */
    public function deleteProgramaPerfil($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar um vínculo');
        $programaPerfil = $this->getObjectManager()->find('\Auth\Model\ProgramaPerfil', $id);
        if (!$programaPerfil)
            throw new EntityException('Programa não encontrado');
        $this->getObjectManager()->remove($programaPerfil);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não está sendo utilizado por outro programa');
        }
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param integer $programaPerfil Identificador de ProgramaPerfil
     * @return array Array contendo: ProgramaPerfil.id Programa.desc_programa Perfil.desc_perfil
     */
    public function getPerfisPProgramaPerfil($idPrograma) {
        $perfisSelecionados = $this->getObjectManager()->createQueryBuilder()
                ->select('Perfil.id as id_perfil', 'Perfil.desc_perfil')
                ->from('\Auth\Model\ProgramaPerfil', 'ProgramaPerfil')
                ->join('ProgramaPerfil.id_programa', 'Programa')
                ->join('ProgramaPerfil.id_perfil', 'Perfil')
                ->orderBy('Perfil.desc_perfil', 'ASC')
                ->where('Programa.id = ' . $idPrograma)
                ->getQuery()
                ->getResult();
        return $perfisSelecionados;
    }

    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param integer $id Identificador de ProgramaPerfil
     * @return array Array contendo: id_programa, id_perfil
     */
    public function getProgramasPPerfil($id) {
        $programaPerfil = $this->getObjectManager()->createQueryBuilder()
                        ->select('Programa.id as id_programa', 'Perfil.id as id_perfil')
                        ->from('\Auth\Model\Programaperfil', 'ProgramaPerfil')
                        ->join('ProgramaPerfil.id_programa', 'Programa')
                        ->join('Programaperfil.id_perfil', 'Perfil')
                        ->orderBy('Programa.desc_programa', 'ASC')
                        ->where('Programa.id = ' . $id)
                        ->getQuery()->getResult();
        return $programaPerfil;
    }

}
