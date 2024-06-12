<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace AdminSocial\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage,
    Zend\Session\Container;

use Admin\Form\Login as LoginForm,
    AdminSocial\Form\Social as SocialForm;

use Base\Mib\SocialOAuth,
    Base\Mib\URL;

class AuthController extends AbstractActionController
{
    public function __construct(ServiceLocatorInterface $serviceLocator) 
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function socialAction(){
        $rede = $this->params()->fromRoute('provedor',false);
        $request = $this->getRequest();
        $form = new SocialForm("social", $this->getEm());
        if($request->isPost())
        {   
            $dados = $request->getPost()->toArray();
            $contaSession = new Container("ContaSocial");
            if(
                !empty($contaSession->email) &&
                $dados["email"]!=$contaSession->email
            )
                $dados["emaildigitado"] = $contaSession->email;
            $form->setData($dados);
            if($form->isValid())
            {
                $service = $this->getServiceLocator()->get("AdminSocial\Service\UsuarioSocial");
                $result = $service->insert($dados);
                if(is_string($result)) 
                {
                    $this->flashMessenger()
                                ->addWarningMessage($result);
                    return $this->redirecionaSocial();
                }
                elseif($result)
                {

                    if($dados["imagem"] && !empty($dados["foto"])){//campo de aceite salvar imagem da rede social
                        $dirPerfil = $_SERVER["DOCUMENT_ROOT"].'/img/fotos/perfil/';
                        $foto = $dados["foto"];
                        $fotoLargura = $fotoAltura = 500;
                        $fotoQualidade = 80;
                        $fotoInterlace = 1;
                        $foto = str_replace(" ", "",preg_replace("/(((width|height|sz|size)=)|=s)[0-9]+/i","$1 ".$fotoLargura,$foto));
                        $imagemOriginal = imagecreatefromstring(file_get_contents($foto));
                        list($imagemOriginalLargura,$imagemOriginalAltura) = getimagesize($foto);
                        $imagemLargura = $fotoLargura;
                        $imagemAltura = round($imagemOriginalAltura * $imagemLargura / $imagemOriginalLargura);
                        if ($imagemAltura > $fotoAltura) {
                            $imagemAltura = $fotoAltura;
                            $imagemLargura = round($imagemOriginalLargura * $imagemAltura / $imagemOriginalAltura);
                        }

                        $imgP = imagecreatetruecolor($imagemLargura, $imagemAltura);
                        $imgPP = $imagemOriginal;

                        $fotoParteX = 0;
                        imagecopyresampled($imgP, $imgPP, $fotoParteX, 0, 0, 0, $imagemLargura, $imagemAltura, $imagemOriginalLargura, $imagemOriginalAltura);

                        imageinterlace($imgP, $fotoInterlace);
                        imagejpeg($imgP, $dirPerfil.$result->getId().".jpg", $fotoQualidade);
                        imagedestroy($imgP);
                        imagedestroy($imgPP);
                    }
                    if($this->validaLogin(false)){
                        $this->flashMessenger()
                            ->addSuccessMessage("Provedor cadastrado com sucesso.");
                    }else{
                        $this->flashMessenger()
                            ->addSuccessMessage("Usuário cadastrado com sucesso.")
                            ->addSuccessMessage("Por favor, entre no seu e-mail, e veja a mensagem que mandamos com as instruções para ativar a conta.")
                            ->addSuccessMessage("Feito isto, basta entrar clicando no botão de rede social ou provedor cadastrado.");
                    }
                    return $this->redirecionaSocial();
                }
                
                if($this->validaLogin(false)){
                    $this->flashMessenger()
                            ->addErrorMessage("Provedor não foi cadastrado.");
                }else{
                    $this->flashMessenger()
                            ->addErrorMessage("Usuário não foi cadastrado.");
                }
                return $this->redirecionaSocial();
            }
        }else{

            $scheme = URL::getInstance()->getScheme();
            $host = URL::getInstance()->getHost();
			if($host!="localhost")
				$scheme = "https";
            $urlCallback = $scheme."://".$host.URL::getInstance()->getRootPath()."social/$rede";
            SocialOAuth::conecta($rede, $urlCallback);
            if(SocialOAuth::getErro()){
                return $this->erroProvedor();
            }
            
            $data = array(
                'nomeexibicao' => SocialOAuth::getNome(),
                'email' => SocialOAuth::getEmail(),
                'oauth_uid' => SocialOAuth::getId(),
                'oauth_provider' => $rede,
                'foto' => SocialOAuth::getFoto(),
                'perfil' => SocialOAuth::getPerfil()
            );
            $contaSession = new Container("ContaSocial");
            $contaSession->email = $data['email'];
            $repo = $this->getEm()->getRepository("AdminSocial\Entity\Usuariosocial");
            $user = $repo->findOneByOauthUid($data["oauth_uid"]);
            if($user){

                if($this->validaLogin(false)){
                     $this->flashMessenger()
                                ->addWarningMessage("Provedor já cadastrado.");
                    return $this->redirecionaSocial();
                }

                // Criando Storage para gravar sessão da authenticação
                $sessionStorage = new SessionStorage("Admin");
                
                $auth = new AuthenticationService;
                $auth->setStorage($sessionStorage); // Definindo o SessionStorage para a auth
                
                $authAdapter = $this->getServiceLocator()->get("AdminSocial\Auth\AdapterSocial");

                $authAdapter->setEmail($data['email']);
                $authAdapter->setOauthUid($data["oauth_uid"]);
                
                $result = $auth->authenticate($authAdapter);
                if($result->isValid())
                {
                    if($auth->getIdentity()['user']->getActivate()){
                        /*
                        $user = $auth->getIdentity();
                        $user = $user['user'];
                        $sessionStorage->write($user,null);
                        */
                        $sessionStorage->write($auth->getIdentity()['user'],null);
                        return $this->redirect()->toRoute('admin-admin');
                    }else{
                        return $this->redirect()->toRoute('admin-logout');
                    }
                }
            }
            $form->setData($data);
        }
        return new ViewModel(array('form'=>$form, 'rede'=>$rede));
    }

    public function erroProvedor(){
        $this->flashMessenger()
            ->addErrorMessage("Erro ao conectar com seu provedor.")
            ->addErrorMessage("Talvez não tenha concedido autorização.");
        return $this->redirect()->toRoute('admin-auth');
    }

    public function redirecionaSocial(){
        if($this->validaLogin(false))
            return $this->redirect()->toRoute('admin-admin/default',array('controller'=>'conta'));
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
}
