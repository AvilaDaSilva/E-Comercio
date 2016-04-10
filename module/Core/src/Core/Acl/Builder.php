<?php

namespace Core\Acl;

use Core\Controller\ActionController;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Db\Sql\Sql;

class Builder extends ActionController implements ServiceManagerAwareInterface {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    public function build() {
        $acl = new Acl();      
        $roles = $this->getRoles();
        $resources = $this->getResources();
        //var_dump($resources);exit;
        $privileges = $this->privilege($roles['auxPrivilege']);
       foreach ($roles['auxRoles']as $role => $parent) {
            $acl->addRole(new Role($role), $parent);
        }
        foreach ($resources as $r) {
            $acl->addResource(new Resource($r));
        }
        
        foreach ($privileges as $role => $privilege) {
            if (isset($privilege['allow'])) {
                foreach ($privilege['allow'] as $p) {
                    $acl->allow($role, $p);
                }
            }
           
            if (isset($privilege['deny'])) {
             
                foreach ($privilege['deny'] as $p) {
                    
                    $acl->deny($role, $p);
                } 
            }
        }
        return $acl;
    }

    public function getRoles() {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Perfil.id', 'Perfil.desc_perfil')
                ->from('Auth\Model\Perfil', 'Perfil')
                ->orderBy('Perfil.desc_perfil');
        $query = $select->getQuery();
        $result = $query->getResult();
        $roles['auxRoles']['Unauthenticated'] = null;
        $roles['auxRoles']['Authenticated'] = 'Unauthenticated';
        $roles['auxPrivilege'] = null;
        foreach ($result as $role) {
            $roles['auxPrivilege'][] = $role;
            $roleUCFirst = ucfirst(strtolower($role['desc_perfil']));
            $roles['auxRoles'][$roleUCFirst] = 'Authenticated';
        }
        return $roles;
    }

    public function getResources() {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Programa.controller_programa', 'Modulo.url_modulo', 'Acao.acao_url')
                ->from('Auth\Model\Acao', 'Acao')
                ->join('Acao.id_programa', 'Programa')
                ->join('Programa.id_modulo', 'Modulo');
        $query = $select->getQuery();
        $result = $query->getResult();

        $resourcesMount = $this->mountResources($result);
        
        $resourcesMount[] = "Auth\Controller\Auth.index";
        $resourcesMount[] = "Auth\Controller\Auth.logout";
        $resourcesMount[] = "Auth\Controller\Auth.login";
        $resourcesMount[] = 'Auth\Controller\Auth.recupera-senha';
        $resourcesMount[] = 'Auth\Controller\Auth.nova-senha';
        $resourcesMount[] = 'Auth\Controller\Auth.naoAutorizado';
        $resourcesMount[] = "DoctrineORMModule\Yuml\YumlController.index";
        $resourcesMount[] = 'SlmQueueDoctrine\Controller\DoctrineWorkerController.process';
        return $resourcesMount;
    }

    private function privilege($roles) {
        if ($roles) {
            foreach ($roles as $r) {
                $select = $this->getObjectManager()->createQueryBuilder()
                        ->select('Programa.controller_programa', 'Modulo.url_modulo', 'Acao.acao_url')
                        ->from('Auth\Model\ProgramaPerfil', 'PPerfil')
                        ->join('PPerfil.id_programa', 'Programa')
                        ->join('Programa.id_modulo', 'Modulo')
                        ->join('Programa.id_filial', 'Filial')
                        ->join('Filial.perfil', 'Perfil')
                        ->join('Programa.acao', 'Acao')
                        ->where('PPerfil.id_perfil = ?1')
                        ->setParameter(1, $r['id']);
                $query = $select->getQuery();
                $result = $query->getResult();
                $resourcesMount = $this->mountResources($result);
                $roleUCFirst = ucfirst(strtolower($r['desc_perfil']));
                $privilege[$roleUCFirst] = array(
                    'allow' => $resourcesMount
                );
            }
            
        } 
        $privilege['Unauthenticated'] = array(
            'allow' => array(
                'Auth\Controller\Auth.index',
                'Auth\Controller\Auth.logout',
                'Auth\Controller\Auth.login',
                'Auth\Controller\Auth.recupera-senha',
                'Auth\Controller\Auth.nova-senha',
                'Auth\Controller\Auth.naoAutorizado',
                'SlmQueueDoctrine\Controller\DoctrineWorkerController.process',
                'Importacao\Controller\ContriService.index',
                'Importacao\Controller\FiscalService.index',
                'Importacao\Controller\ContabilService.index',
                'Importacao\Controller\ImportarXml.push-job',
                'Importacao\Controller\Importar.save',
        ));
        $privilege['Authenticated'] = array(
            'allow' => array(
                'Auth\Controller\Auth.index',
                'Auth\Controller\Auth.logout',
                'Auth\Controller\Auth.login',
                'Auth\Controller\Auth.naoAutorizado',
                'Auth\Controller\SelecionarPerfil.index',
                'Auth\Controller\SelecionarPerfil.carregar-sessao',
                'Auth\Controller\SelecionarPerfil.selecionar-perfil',
                'Auth\Controller\SelecionarPerfil.selecionar-programa',
                'Auth\Controller\SelecionarPerfil.selecionar-filial',
                'Auth\Controller\Padrao.save',
                'Importacao\Controller\ContriService.index',
                'Importacao\Controller\FiscalService.index',
                'Importacao\Controller\ContabilService.index',
                'Importacao\Controller\ImportarXml.push-job',
                'Importacao\Controller\Importar.save',
            )
        );
        
        return $privilege;
    }

    private function mountResources($resources) {
        $resourcesMount = array();
        foreach ($resources as $re) {
            $modulo = $re['url_modulo'];    
            $controller = $re['controller_programa'];
            $action = $re['acao_url'];
            $resourcesMount[] = "$modulo\\Controller\\$controller.$action";
        }
        return $resourcesMount;
    }

}
