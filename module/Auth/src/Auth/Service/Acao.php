<?php

namespace Auth\Service;
 
use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Acao
 * 
 * @category Auth
 * @package Service
 * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>  
 */
class Acao extends Service {

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param string $search Filtro para buscar ações, não obrigatório
     * @return array Array contendo: Acao.id, Acao.desc_acao, Acao.acao_url, Programa.desc_programa, Programa.controller_programa, Filial.nome_filial, Filial.desc_modulo_filial
     */
    public function getAcoes($search = null) {
        $select = $this->getObjectManager()
                ->createQueryBuilder()
                ->select('Acao.id', 'Acao.desc_acao', 'Acao.acao_url', 'Programa.desc_programa', 'Programa.controller_programa', 'Filial.nome_filial', 'Filial.desc_modulo_filial')
                ->from('\Auth\Model\Acao', 'Acao')
                ->join('Acao.id_programa', 'Programa')
                ->join('Programa.id_filial', 'Filial')
                ->orderBy('Acao.desc_acao', 'ASC');

        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Acao no banco de dados
     * @return \Auth\Model\Acao 
     */
    public function saveAcao($data) {
        if ($data['id'] != '') {
            $acao = $this->getObjectManager()->find('\Auth\Model\Acao', $data['id']);
        } else {
            $acao = new \Auth\Model\Acao();
        }
        $filters = $acao->getInputFilter();
        $filters->setData($data);
        if (!$filters->isValid())
            throw new EntityException('Dados inválidos');
        $data = $filters->getValues();
        $acao->desc_acao = $data['desc_acao'];
        $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $data['id_programa']);
        if (!$programa)
            throw new EntityException('Programa não encontrado');
        $acao->id_programa = $programa;
        $acao->acao_url = $data['acao_url'];
        $this->getObjectManager()->persist($acao);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {            
            throw new EntityException('Ação já está cadastrada ou não foi possível conectar ao banco de dados no momento');
        }
        return $acao;
    }
    
    /**
     * @author Cezar Junior de Souza <cezar08@unochapeco.edu.br>  
     * @param integer $id Identificador do acao
     * @return void
     */
    public function deleteAcao($id) {
        if (!$id)
            throw new EntityException('É preciso passar um identificador para deletar uma ação');
        $acao = $this->getObjectManager()->find('\Auth\Model\Acao', $id);
        if (!$acao)
            throw new EntityException('Ação não encontrada');
        $this->getObjectManager()->remove($acao);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao excluir, verifique se o registro não está sendo utilizado por outro programa');
        }
    }
    /**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id Identificador da filial
     * @return Array Array contendo Programa.id, Programa.desc_programa
     */
    public function getProgramasPFilial($id){
        $select = $this->getObjectManager()->createQueryBuilder()
            ->select("Programa.id", "Programa.desc_programa")
            ->from("\Auth\Model\Programa", "Programa")
            ->join("Programa.id_filial", "Filial")
            ->orderBy('Programa.desc_programa', 'ASC')
            ->where("Filial.id = ?1 ")
            ->setParameter(1, $id);
            $dadosProgramas = $select->getQuery()->getResult();
            return $dadosProgramas;
    }
    
    public function salvarAcaoParams($data) {
        if ($data['id'] != '') {
            $acao = $this->getObjectManager()->find('\Auth\Model\Acao', $data['id']);
        } else {
            $acao = new \Auth\Model\Acao();
        }
//        var_dump(mb_strtoupper($data['desc_programa']));
        if($data['desc_programa'] != '')         
           $programa = $this->getObjectManager()->getRepository('Auth\Model\Programa')->findOneBy(array('desc_programa' => mb_strtoupper($data['desc_programa'])));
        else
           $programa = $this->getObjectManager()->find('\Auth\Model\Programa', $data['id_programa']); 
                    
        $filters = $acao->getInputFilter();
        $filters->setData($data);        
        $data = $filters->getValues();
        $acao->setDesc_acao($data['desc_acao']);            
        $acao->setId_programa($programa);
        $acao->setAcao_url($data['acao_url']);
        $this->getObjectManager()->persist($acao);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {            
            throw new EntityException('Ação já está cadastrada ou não foi possível conectar ao banco de dados no momento');
        }
        return $acao;
    }

}
