<?php

namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

abstract class CrudController extends AbstractActionController
{

    public function __construct(){
        
    }    
    
    public function indexAction() {
        
        $list = $this->getEm()
                ->getRepository($this->entity)
                ->findAll();
        
        $page = $this->params()->fromRoute('page');
        
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
                ->setDefaultItemCountPerPage(10);

        return new ViewModel(array('data'=>$paginator,'page'=>$page));
        
    }

    public function newAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $service = $this->getServiceLocator()->get($this->service);
                $result = $service->insert($request->getPost()->toArray());
                if(is_string($result)){
                    $this->flashMessenger()
                                ->addWarningMessage(strtoupper($this->nome).$result);
                }elseif($result){
                    $this->flashMessenger()
                                ->addSuccessMessage(strtoupper($this->nome)." cadastrado com sucesso.");
                }else{
                    $this->flashMessenger()
                                ->addErrorMessage(strtoupper($this->nome)." não foi cadastrado.");
                }
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }
        }
        
        return new ViewModel(array('form'=>$form));
    }
    
    public function editAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $array = $request->getPost()->toArray();
            $form->setData($array);
            if($form->isValid())
            {
                if($this->permissaoAlterar($array["id"])){
                    $service = $this->getServiceLocator()->get($this->service);
                    $result = $service->update($request->getPost()->toArray());
                    if(is_string($result)){
                        $this->flashMessenger()
                                    ->addWarningMessage(strtoupper($this->nome).$result);
                    }elseif($result){
                        $this->flashMessenger()
                                    ->addSuccessMessage(strtoupper($this->nome)." alterado com sucesso.");
                    }else{
                        $this->flashMessenger()
                                    ->addErrorMessage(strtoupper($this->nome)." não foi alterado.");
                    }
                }
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }
        }
        
        $entity = $this->permissaoAlterar($this->getId());
        if($entity)
            $form->setData($entity->toArray());
        return new ViewModel(array('form'=>$form));
    }
    
    public function deleteAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();

        if($request->isPost())
        {
            $id=$this->getId();
            if($this->permissaoAlterar($id)){
                $service = $this->getServiceLocator()->get($this->service);
                if($service->delete($id)){
                    $this->flashMessenger()
                                ->addWarningMessage(strtoupper($this->nome)." apagado com sucesso.");
                }else{
                    $this->flashMessenger()
                                ->addErrorMessage(strtoupper($this->nome)." não foi apagado.");
                }
            }
            return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
        }
        
        $entity =  $this->permissaoAlterar($this->getId());
        if($entity)
            $form->setData($entity->toArray());

        return new ViewModel(array('form'=>$form));
    }

    public function permissaoAlterar($id){
        $repository = $this->getEm()->getRepository($this->entity);
        $entity = $repository->findOneById($id);
        return $entity;
    }

    public function getId(){
        $id=0;
        $request = $this->getRequest();
        if($request->isPost()){
            $id = $request->getPost("id");
        }else{
            $id=$this->params()->fromRoute('id',0);
        }
        return $id;
    }

    public function usuarioAdmin(){
        $usuario = $this->getEm()->find(
            "Admin\Entity\Usuario",
            $this->usuarioLogado()->getId()
        );
        $acl = $this->getServiceLocator()->get("Acl\Permissions\Acl");
        return $acl->isAdmin($usuario);
    }

    /**
    * @return ServiceLocatorInterface
    */
    public function getServiceLocator(){
        return $this->serviceLocator;
    }
    
    /**
    *
    * @return EntityManager
    */
    public function getEm(){
        if(null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this->em;
    }

    protected function usuarioLogado(){
        $usuarioIdentidade = $this->getServiceLocator()->get('ViewHelperManager')->get('UsuarioIdentidade');
        return $usuarioIdentidade("Admin");
    }

    protected function validaLogin($redirect = true){
        $usuario = $this->usuarioLogado();
        if($redirect)
            if(!$usuario)
                return $this->redirect()->toRoute('admin-admin');
        return $usuario;
    }

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;
    protected $em;
    protected $service;
    protected $entity;
    protected $form;
    protected $route;
    protected $controller;
    protected $nome;
    protected $admin = true;
    protected $logadoId = false;
}
