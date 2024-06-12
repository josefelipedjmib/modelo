<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\ServiceManager\ServiceLocatorInterface,
    Doctrine\Common\Collections\Criteria;
use Admin\Form\Usuario as UsuarioForm;
use Base\Mail\Mail;

class IndexController extends AbstractActionController
{
    public function __construct(ServiceLocatorInterface $serviceLocator) 
    {   
        $this->serviceLocator = $serviceLocator;
        $this->entity = "Admin\Entity\Usuario";
        $this->service = "Admin\Service\Usuario";
        $this->controller = "index";
        $this->route = "admin-activate";
        $this->config = $this->getServiceLocator()->get('config');
        $this->useSocial = $this->config["usaprovedorsocial"];
        $this->tipoLogin = $this->config["tipodelogin"];
    }

    public function testeAction()
    {
        $view = $this->getServiceLocator()->get('View');

        $emailConfig = $this->config['email']['connection']['params'];
        $emailData = array(
            "status" => "Teste de e-mail",
            "usuario" => "José Felipe Paquetá",
            "assunto" => "Teste do sistema modelo"
        );
        $mail = new Mail($emailConfig, $view, 'notificacao');
        // $mail->get()->SMTPDebug = 2;
        $enviado = $mail->setData($emailData)
            ->setReplyTo("josefelipe@rioeduca.net")
            ->setReplyToName("nome")
            ->setSubject($emailData["assunto"])
            ->prepare()
            ->send();
        if($enviado){
            echo "E-mail enviado corretamente.";
        }else{
            echo "Erro ao enviar e-mail. Tente novamente.";
        }

        exit;
        return new ViewModel();
    }
    
    public function indexAction(){
        if(is_array($this->usuarioLogado()))
            return $this->redirect()->toRoute('admin-logout');
        return new ViewModel(['sl'=>$this->getServiceLocator()]);
    }
    
    public function registerAction() 
    {
        $this->validaLogin();
        $form = new UsuarioForm("usuario", [], $this->getEm());
        
        $camposRemover = [
            "nome",
            "snome",
            "activate",
            "senha",
            "sexo",
            "novasenha",
            "confirmacao",
            "role"
        ];
        if($this->tipoLogin == 'cpf'){
           $camposRemover = array_merge($camposRemover,['datanasci']);
        }else{
            $camposRemover = array_merge($camposRemover,['cpf']);
        }

        $form->removerCampos($camposRemover);
        $form->get("submit")->setValue("Cadastrar");
        $request = $this->getRequest();
        
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $service = $this->getServiceLocator()->get("Admin\Service\Usuario");
                $result = $service->insert($request->getPost()->toArray());
                if(is_string($result)) 
                {
                    $this->flashMessenger()
                                ->addWarningMessage($result);
                    return new ViewModel(array('form'=>$form));
                }
                elseif($result)
                {
                    $this->flashMessenger()
                                ->addSuccessMessage("Usuário cadastrado com sucesso.")
                                ->addSuccessMessage("Verifique o seu e-mail, para o qual, enviamos uma mensagem para liberar o seu acesso.")
                                ->addSuccessMessage("Caso não apareça a mensagem, verifique se está no SPAM ou lixo eletrônico.");
                    return $this->redirect()->toRoute('admin-auth');
                }
                
                $this->flashMessenger()
                            ->addErrorMessage("Usuário não foi cadastrado.");
                return $this->redirect()->toRoute('admin-register');
            }
        }
        
        
        return new ViewModel(array('form'=>$form));
    }
    
    public function activateAction()
    {
        $this->validaLogin();
        $form =  new UsuarioForm("ativacao", [], $this->getEm());
        $form->removerValidador($form->getInputFilter()->get("email"), '\DoctrineModule\Validator\NoObjectExists');

        
        $camposManter = [
            "id",
            "email",
            "secutiry",
            "submit"
        ];
        if($this->tipoLogin == 'cpf'){
           $camposManter = array_merge($camposManter,['cpf']);
        }else{
            $camposManter = array_merge($camposManter,['senha','novasenha']);
        }
        $form->manterApenasCampos($camposManter);
        $request = $this->getRequest();
        $repository = $this->getEm()->getRepository($this->entity);
        if($request->isPost())
        {
            $form->setData($request->getPost());
            if($form->isValid())
            {
                $service = $this->getServiceLocator()->get($this->service);
                $usuario = $request->getPost()->toArray();
                $entity = $repository->find($usuario['id']);
                if($entity){
                    $confere = $usuario['email'] === $entity->getEmail();
                    if($this->useSocial && !$confere){
                        $i=0;
                        $social = $entity->getSociais();
                        while($i<$social->count() && !$confere){
                            $confere = $social[$i]->getEmail()===$usuario["email"];
                            $i++;
                        }
                    }
                    $usuario["email"] = $entity->getEmail();
                }
                if(!($entity && $confere)){
                    $this->flashMessenger()
                        ->addErrorMessage("E-mail não confere.");
                    return $this->redirect()->toRoute($this->route);
                }
                if($entity->getCPF() != $usuario["cpf"]){
                    $this->flashMessenger()
                        ->addErrorMessage("CPF não confere.");
                    return $this->redirect()->toRoute($this->route);
                }
                $ativo = $service->activate($entity->getActivationkey());
                if($ativo || $ativo=="ativado"){
                    $this->flashMessenger()
                                ->addSuccessMessage("Usuário com acesso liberado.");
                }else{
                    $this->flashMessenger()
                        ->addErrorMessage("Usuário não encontrado.");
                }
                if($service->update($usuario)){
                    $this->flashMessenger()
                                ->addSuccessMessage("Senha alterada com sucesso.");
                }else{
                    $this->flashMessenger()
                                ->addErrorMessage("A senha não foi alterada.");
                }
                return $this->redirect()->toRoute($this->route);
            }
        }
        $key = $this->params()->fromRoute('key',0);
        $entity = $repository->findOneByActivationkey($key);
        if($entity){
            $array = $entity->toArray();
            unset($array['senha']);
            unset($array['email']);
            $form->setData($array);
            return new ViewModel(array('form'=>$form, 'exibeform' => true));
        }
        $flashMessenger = $this->flashMessenger();
        foreach($flashMessenger->getErrorMessages() as $msg){
            $flashMessenger->addErrorMessage($msg);
        }
        foreach($flashMessenger->getSuccessMessages() as $msg){
            $flashMessenger->addSuccessMessage($msg);
        }
        $temError = (count($form->getMessages())>0)?true:false;
        if(!($temError || $flashMessenger->hasSuccessMessages())){
            $this->flashMessenger()
                    ->addErrorMessage("Usuário não encontrado.");
        }
        return new ViewModel(array('form'=>$form, 'exibeform' => $temError));
    }
    public function senhaAction() 
    {
        $this->validaLogin();
        if($this->tipoLogin == 'email'){     
            $form =  new UsuarioForm("senha", [], $this->getEm());
            $form->removerValidador($form->getInputFilter()->get("email"), '\DoctrineModule\Validator\NoObjectExists');
            $form->removerCampos([
                "id",
                "nomeexibicao",
                "nome",
                "snome",
                "email",
                "datanasci",
                "sexo",
                "activate",
                "senha",
                "novasenha",
                "confirmacao",
                "security",
                "aceite",
                "role"
            ]);
            $form->get("submit")->setValue("Redefinir");
            $request = $this->getRequest();
            if($request->isPost())
            {
                $form->setData($request->getPost());
                if($form->isValid())
                {
                    $service = $this->getServiceLocator()->get($this->service);
                    $usuario = $request->getPost()->toArray();
                    $repository = $this->getEm()->getRepository($this->entity);
                    $entity = $repository->findOneByEmail($usuario['email']);
                    $emails = [];
                    if($this->useSocial && !$entity){
                        $sql ='select * from usuario where id=(select usuario from usuariosocial where email="'.$usuario['email'].'")';
                        $stmt = $this->getEm()->getConnection()->prepare($sql);
                        $stmt->execute();
                        $arr = $stmt->fetch();
                        if(!empty($arr)){
                            $entity = $repository->find($arr["id"]);
                            $emails[] = $usuario['email'];
                        }
                    }
                    if($entity){
                        $service->enviaEmail(
                            $entity,
                            "Redefinição de senha",
                            'senha',
                            $emails
                        );
                        $this->flashMessenger()
                                    ->addSuccessMessage("E-mail enviado para ".$entity->getEmail().".")
                                    ->addSuccessMessage("Enviamos uma mensagem para você com link de redefinição de senha.")
                                    ->addSuccessMessage("Caso não apareça a mensagem, verifique se está no SPAM ou lixo eletrônico do e-mail.");

                    }else{
                        $this->flashMessenger()
                            ->addErrorMessage("E-mail não cadastrado no sistema.");
                    }
                    return $this->redirect()->toRoute('admin-email');
                }
            }
            return new ViewModel(array('form'=>$form));
        }
        return new ViewModel();
    }

    public function faleconoscoAction() 
    {
        return new ViewModel();
    }

    public function politicadeprivacidadeAction() 
    {
        return new ViewModel();
    }

    public function termosdeservicoAction() 
    {
        return new ViewModel();
    }

    /**
     *
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

    private function validaLogin(){
        $usuario = $this->usuarioLogado();
        if($usuario)
            return $this->redirect()->toRoute('admin-admin');
    }

    private function usuarioLogado(){
        $usuarioIdentidade = $this->getServiceLocator()->get('ViewHelperManager')->get('UsuarioIdentidade');
        return $usuarioIdentidade("Admin");
    }

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;
    protected $em;
    protected $config;
    protected $useSocial = false;
    protected $tipoLogin = "email";
}
