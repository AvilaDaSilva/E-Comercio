<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Programa
 * 
 * @category Auth
 * @package Service
 * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>  
 */
class Programa extends Service {

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param string $search Filtro para buscar programas, não obrigatório
     * @return array Array contendo: Programa.id, Programa.desc_programa
     */
    public function getProgramas($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Programa.id', 'Programa.desc_programa')
                ->from('\Auth\Model\Programa', 'Programa')
                ->orderBy('Programa.desc_programa', 'ASC')
                ->where("Programa.desc_programa LIKE ?1")
                ->setParameter(1, '%' . $search . '%');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Programa no banco de dados
     * @return \Auth\Model\Programa 
     */
    public function savePrograma($data) {
        if ($data['id'] != '')
            $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $data['id']);
        else
            $programa = new \Auth\Model\Programa();
        $filters = $programa->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid())
            throw new EntityException('Dados inválidos');
        $data = $filters->getValues();
        $programa->controller_programa = $data['controller_programa'];
        $programa->desc_programa = $data['desc_programa'];
        $filial = $this->getObjectManager()->find('\Auth\Model\Filial', $data['id_filial']);
        if (!$filial)
            throw new EntityException('Filial não encontrada');
        $programa->id_filial = $filial;
        $modulo = $this->getObjectManager()->find('\Auth\Model\Modulo', $data['id_modulo']);
        if (!$modulo)
            throw new EntityException('Módulo não encontrado');
        $programa->id_modulo = $modulo;
        $programa->menu = $data['menu'];
        $this->getObjectManager()->persist($programa);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
        return $programa;
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>  
     * @param integer $id Identificador do programa
     * @return void
     */
    public function deletePrograma($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar um programa');
        $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $id);
        if (!$programa)
            throw new EntityException('Programa não encontrado');
        $this->getObjectManager()->remove($programa);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não está sendo utilizado por outro programa');
        }
    }

    public function getProgramasPPerfil($perfil) {

        $select = $this->getObjectmanager()->createQueryBuilder()
                ->select('Modulo.id as id_modulo','Modulo.desc_modulo as desc_modulo', 'Programa.id', 'Programa.desc_programa', 'Programa.menu', 'Programa.icone')
                ->from('Auth\Model\programaPerfil', 'PPerfil')
                ->join('PPerfil.id_programa', 'Programa')
                ->join('Programa.id_modulo', 'Modulo')
                ->join('PPerfil.id_perfil', 'Perfil')
                ->where('Perfil.id = ?1')
                ->orderBy('Modulo.id')
                ->addOrderBy('Programa.desc_programa')
                ->setParameter(1, $perfil)
                ->distinct('Programa.id');
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getSubPrograma($id) {
        $select = $this->getObjectmanager()->createQueryBuilder()
            ->select('SubPrograma.id', 'SubPrograma.desc_programa_nivel', 'SubPrograma.controller_programa_nivel', 'SubPrograma.icone', 'Programa.id as idPrograma')
            ->from('Auth\Model\ProgramaSubNivel', 'SubPrograma')
            ->join('SubPrograma.id_programa', 'Programa')
            ->where('Programa.id = ?1')
            ->setParameter(1, $id);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function verificaCloneArray($input) {
        $serialized = array_map('serialize', $input);
        $unique = array_unique($serialized);
        return array_intersect_key($input, $unique);
    }

    function teste($array, $key, $sort_flags = SORT_REGULAR) {
        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v[$key];
                    } else {
                        foreach ($key as $key_key) {
                            $sort_key .= $v[$key_key];
                        }
                        $sort_flags = SORT_STRING;
                    }
                    $mapping[$k] = $sort_key;
                }
                asort($mapping, $sort_flags);
                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }

}
