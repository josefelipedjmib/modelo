<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\View\Model\ViewModel,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\Form\Element;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;

use Base\Controller\CrudController;

class UsuariosController extends CrudController 
{

    public function __construct(ServiceLocatorInterface $serviceLocator) 
    {
        $this->serviceLocator = $serviceLocator;
        $this->entity = "Admin\Entity\Usuario";
        $this->form = "Admin\Form\Usuario";
        $this->service = "Admin\Service\Usuario";
        $this->controller = "usuarios";
        $this->route = "admin-admin/default";
        $this->nome = "usuário";
    }

    public function indexAction() {
        
        $list = $this->getEm()
                ->getRepository($this->entity)
                ->getCommonUsers();
        
        $page = $this->params()->fromRoute('page');
        
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
                ->setDefaultItemCountPerPage(10);

        return new ViewModel(array('data'=>$paginator,'page'=>$page));
        
    }

    public function newAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $this->notificaEmail($form);
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
        $this->notificaEmail($form);
        $form->removerCampos(array(
            "aceite",
            "senha"
        ));
        $form->removerValidador($form->getInputFilter()->get("email"), '\DoctrineModule\Validator\NoObjectExists');
        if(!$this->admin){
            $form->removerCampos([
                "novasenha",
                "confirmacao"
            ]);
            $form->getInputFilter()->remove("role");
            $form->getInputFilter()->remove("activate");
        }
        $form->getInputFilter()->remove("novasenha");
        $form->getInputFilter()->remove("confirmacao");
        $form->getInputFilter()->remove("senha");
        
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            $form->setData($post);
            if($form->isValid($post))
            {
                $service = $this->getServiceLocator()->get($this->service);
                $result = $service->update($post->toArray());
                if($result=="email"){
                    $this->flashMessenger()
                                ->addWarningMessage("Você alterou o e-mail da sua conta e precisamos validar esta informação.")
                                ->addWarningMessage("Por favor, entre no seu e-mail, e veja a mensagem que mandamos com as instruções para reativar a conta.");
                                
                    return $this->redirect()->toRoute('admin-logout');
                }elseif(is_string($result)){
                    $this->flashMessenger()
                                ->addWarningMessage(strtoupper($this->nome).$result);
                }elseif($result){
                    $this->flashMessenger()
                                ->addSuccessMessage(strtoupper($this->nome)." alterado com sucesso.");
                }else{
                    $this->flashMessenger()
                                ->addErrorMessage(strtoupper($this->nome)." não foi alterado.");
                }
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }
        }

        $id = $this->getId();
        $repository = $this->getEm()->getRepository($this->entity);
        $entity = $repository->find($id);
        $roles = [];
        $array = [];
        $isAdmin = false;
        if($entity)
        {
            $roles = $this->listarRoles($entity);
            $isAdmin = $roles['isAdmin'];
            $roles = $roles['roles'];
            $array = $entity->toArray();
            unset($array['senha']);
            unset($array['role']);
            $array["datanasci"] = $entity->getDados()->getDatanasci();
            $array["sexo"] = $entity->getDados()->getSexo();
            $form->setData($array);
            $this->camposAdmin($form,  [
                    'roles'=>$roles,
                    'isAdmin'=>$this->admin
                ]);
        }
        return new ViewModel(array('form'=>$form));
    }
    
    public function deleteAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $form->removerCampos(array(
            "aceite"
        ));
        $request = $this->getRequest();

        if($request->isPost())
        {
            $service = $this->getServiceLocator()->get($this->service);
            $id = $this->getId();
            if($service->delete($id)){
                unlink($_SERVER["DOCUMENT_ROOT"].'/img/fotos/perfil/'.$id.".jpg");
                $this->flashMessenger()
                            ->addWarningMessage(strtoupper($this->nome)." apagado com sucesso.");
            }else{
                $this->flashMessenger()
                            ->addErrorMessage(strtoupper($this->nome)." não foi apagado.");
            }
            if($this->admin){
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }else{
                return $this->redirect()->toRoute('admin-logout');
            }
        }
        
        $id = $this->getId();
        $repository = $this->getEm()->getRepository($this->entity);
        $entity = $repository->find($id);
        $roles=[];
        $isAdmin = false;
        $roles = $this->listarRoles($entity);
        $isAdmin = $roles['isAdmin'];
        $roles = $roles['roles'];
        
        if($entity){
            $form->setData($entity->toArray());
        }
        $this->camposAdmin($form, [
                'roles'=>$roles,
                'isAdmin'=>$this->admin
        ]);

        return new ViewModel(array('form'=>$form));
    }

    public function passwordAction()
    {
        $form = $this->getServiceLocator()->get($this->form);
        $this->notificaEmail($form);
        $form->removerCampos(array(
            "aceite",
            "nomeexibicao",
            "nome",
            "snome",
            "datanasci",
            "sexo",
            "email",
            "role",
            "activate",
            "notifica"
        ));
       
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $post = $request->getPost();
            $form->setData($post);
            if($form->isValid($post))
            {
                $service = $this->getServiceLocator()->get($this->service);
                $result = $service->password($post->toArray());
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
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }
        }

        $id = $this->getId();
        $repository = $this->getEm()->getRepository($this->entity);
        $entity = $repository->find($id);
        if($entity)
        {
            $array = $entity->toArray();
            unset($array['senha']);
            $form->setData($array);
        }
        return new ViewModel(array('form'=>$form));
   }

    public function getId(){
        $id = $this->usuarioLogado()->getId();
        if($this->admin){
            $request = $this->getRequest();
            if($request->isPost()){
                $id = $request->getPost("id");
            }else{
                $id=$this->params()->fromRoute('id',0);
            }
        }
        return $id;
    }

    public function listarRoles($entity){
        $roles = [];
        $isAdmin = false;
        foreach($entity->getRole() as $role){
            $roles[] = $role->getId();
            if($role->getIsAdmin()){
                $isAdmin = true;
            }
        }

        return ['roles'=>$roles, 'isAdmin'=>$isAdmin];
    }

    public function notificaEmail($form){
        if($this->admin){
            $email = new Element\Checkbox("notifica");
            $email->setLabel("Notificar usuário por e-mail?: ");
            $form->add($email);
        }
    }

    public function camposAdmin($form, $options){
        $roles = $options['roles'];
        $isAdmin = $options['isAdmin'];
        if(empty($roles))
            $roles[] = 2;
        $role = $form->get("role");
        $role->setValue($roles[0]);
        if(!$isAdmin){
            $form->removerCampos([
                    "role",
                    "activate"
                ]);
        }
    }
}
