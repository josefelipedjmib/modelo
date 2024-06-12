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
    Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage,
    Zend\Session\Container;

use Admin\Form\Login as LoginForm;

use Base\Mib\URL;

class AuthController extends AbstractActionController
{
    public function __construct(ServiceLocatorInterface $serviceLocator) 
    {
        $this->serviceLocator = $serviceLocator;
        $this->entity = "Admin\Entity\Usuario";
        $this->config = $this->getServiceLocator()->get('config');
        $this->tipoLogin = $this->config["tipodelogin"];
    }

    public function indexAction()
    {
        $this->validaLogin();
            
        $request = $this->getRequest();
        $form = new LoginForm("login");
        $camposRemover = [
        ];
        if($this->tipoLogin == 'cpf'){
           $camposRemover = array_merge($camposRemover,['email','password','cpfid']);
        }else{
            $camposRemover = array_merge($camposRemover,['cpf', 'cpfid']);
        }
        $form->removerCampos($camposRemover);

        if($request->isPost())
        {
            $data = $request->getPost()->toArray();
            if(
                $this->tipoLogin == 'cpf'
                && isset($data['cpfid'])
            ){
                $form->manterApenasCampos(['cpfid','password','submit','security']);
            }
            $form->setData($data);
            if($form->isValid())
            {
                $authService = $this->getServiceLocator()->get("Admin\Service\Auth");
                
                if(
                    $this->tipoLogin == "cpf" 
                    && !isset($data['cpfid'])
                ){
                    $resultado = $authService->preparaAutenticacaoCPF($data['cpf']);
                    if($resultado == "ok")
                    {
                        $this->flashMessenger()
                                    ->addSuccessMessage("Você irá receber um e-mail com a sua senha de acesso.")
                                    ->addSuccessMessage("Verifique o seu e-mail, para poder concluir o seu login.");
                        $form = new LoginForm("login");
                        $data["cpfid"] = $data["cpf"];
                        $form->setData($data);
                        $form->manterApenasCampos(['cpfid','password','submit','security']);
                        return new ViewModel(array('form'=>$form));
                    }elseif($resultado == "error"){
                        $this->flashMessenger()
                                ->addWarningMessage("Erro ao tentar enviar e-mail. Por favor, tente novamente.");
                        return $this->redirect()->toRoute('admin-logout');
                    }
                }elseif(
                    $this->tipoLogin == "email" 
                    || (
                        $this->tipoLogin == "cpf" 
                        && isset($data['cpfid'])
                    )
                ){
                    $authAdapter = $this->getServiceLocator()->get('Admin\Auth\Adapter');
                    if(isset($data['cpfid']))
                        $authAdapter->setUsername($data['cpfid']);
                    else
                        $authAdapter->setUsername($data['email']);
                    $authAdapter->setPassword($data['password']);
                    $result = $authService->autenticar($authAdapter);
                    if($result=="ok")
                    {
                            return $this->redirect()->toRoute('admin-admin');
                    }elseif($result=="desativado"){
                        $this->logStatus(
                            "usuariologin",
                            "desativado"
                        );
                        $this->flashMessenger()
                                    ->addWarningMessage("Usuário desativado! Entre em contato conosco.");
                        return $this->redirect()->toRoute('admin-logout');
                    }
                }
            }
            $this->logStatus(
                "usuariologin",
                "erro"
            );
            $this->flashMessenger()
                        ->addErrorMessage("Usuário ou senha não conferem. Por favor, tente novamente.");
        }
        return new ViewModel(array('form'=>$form));
    }
    
    public function logoutAction()
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("Admin"));
        $auth->clearIdentity();
        unset($_SESSION['Admin']['isAdmin']);
        return $this->redirect()->toRoute('admin-auth');
    }

    private function validaLogin($redirect = true){
        $usuarioIdentidade = $this->getServiceLocator()->get('ViewHelperManager')->get('UsuarioIdentidade');
        $usuario = $usuarioIdentidade("Admin");
        if($redirect)
            if($usuario)
                return $this->redirect()->toRoute('admin-admin');
        return $usuario;
    }

    public function logStatus($acao,$status){
        $entity = $this->entity;
        // $usuarioSistema = '000001'; Não irá utilizar usuário padrão, pois aqui só vai logar se tiver usuário válido.
        $usuario = false;
        $logado = $this->validaLogin(false);
        if(is_array($logado)){
            $logado = $logado['user'];
        }
        if($logado){
            $usuario = $this->getEm()->find(
                $entity,
                $logado->getId()
            );
        }
        if($acao == "usuariologin"){
            if(!$usuario){
                $usuario = $this->getEm()->getRepository($entity)->findOneByEmail($this->getRequest()->getPost()->toArray()['email']);
            }
            $erros = $this->getEm()->getRepository("Admin\Entity\LogSystem")->findBy([
                'usuario' => $usuario,
                'acao' => 'usuariologin',
                'status' => 'erro'
            ]);
            if($status == "erro"){
                if(count($erros)>4){
                    $usuario->setActivate(false);
                    $this->getEm()->flush();
                }
            }else{
                foreach($erros as $erro){
                    $this->getEm()->remove($erro);
                    $this->getEm()->flush();
                }
            }
        }
        // Não tem usuário padrão
        if($usuario){
            $registro = new \Admin\Entity\Logsystem();
            $registro->setAcao($acao);
            $registro->setStatus($status);
            $registro->setUsuario($usuario);
            $ip = \Base\Mib\URL::getInstance()->getIPTratado();
            $registro->setIpa($ip[0]);
            $registro->setIpb($ip[1]);
            $registro->setIpc($ip[2]);
            $registro->setIpd($ip[3]);
            $this->getEm()->flush();
        }
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


    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;
    protected $em;
    protected $config;
    protected $tipoLogin = "email";
    protected $entity;
}
