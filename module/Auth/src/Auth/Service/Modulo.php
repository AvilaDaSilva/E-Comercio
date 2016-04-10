<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

/**
 * Serviço para manipulação de dados da entidade Filial
 * 
 * @category Auth
 * @package Service
 * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>
 */
class Modulo extends Service{
	/**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param string $search Filtro para busca Modulos, não obrigatório
     * @return array Array contendo: Modulo.id, Modulo.desc_modulo
     */
	public function getModulos($search = null){
		$select = $this->getObjectManager()
		->createQueryBuilder()
		->select('Modulo.id', 'Modulo.desc_modulo')
		->from('\Auth\Model\Modulo', 'Modulo')  
		->orderBy('Modulo.desc_modulo', 'ASC')
		->where("Modulo.desc_modulo LIKE ?1")
		->setParameter(1, '%'.$search. '%');
		$query = $select->getQuery();
		$result = $query->getResult();
		return $result;
	}
	/**
     * @author Willian Gustavo Mendo <willianmendo@unochapeco.edu.br>     
     * @param array $data Dados para armazenar um objeto Modulo no banco de dados
     * @return \Auth\Model\Modulo
     */
	public function saveModulo($data){
		if ($data['id'] != '') {
			$modulo = $this->getObjectManager()->find('\Auth\Model\Modulo', $data['id']);
		}
		else {
			$modulo = new \Auth\Model\Modulo();
		}          
		$filters = $modulo->getInputFilter();
		$filters->setData($data);		
		$data = $filters->getValues();                   
		$modulo->desc_modulo = $data['desc_modulo'];
                $modulo->url_modulo = $data['url_modulo'];
		$this->getObjectManager()->persist($modulo);
		try{
			$this->getObjectManager()->flush();
		}catch(exception $e){
			throw new EntityException ('Tente novamente mais tarde');
		}
		return $modulo;	
	}
	/**
     * @author Willian Gustavp Mendo <willianmendo@unochapeco.edu.br>  
     * @param integer $id Identificador do Modulo
     * @return void
     */
	public function deleteModulo($id){
		if (!$id)
			throw new EntityException('É preciso passar um identificador para deletar um módulo');
		$modulo = $this->getObjectManager()->find('\Auth\Model\Modulo', $id);
		if (!$modulo)
			throw new EntityException('Módulo não encontrado');
		$this->getObjectManager()->remove($modulo);
		try {
			$this->getObjectManager()->flush();
		} catch (\Exception $e) {
			throw new EntityException('Erro ao excluir, verifique se o registro não possui vínculos');
		}
	}
}