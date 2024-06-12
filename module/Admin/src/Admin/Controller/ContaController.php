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
    Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Paginator\Paginator;

use Base\Form\Image as ImageForm,
    AdminSocial\Form\Social as SocialForm;

class ContaController extends UsuariosController 
{

    public function __construct(ServiceLocatorInterface $serviceLocator) 
    {
        parent::__construct($serviceLocator);
        $this->entity = "Admin\Entity\Usuario";
        $this->entitySocial = "AdminSocial\Entity\UsuarioSocial";
        $this->entityTelefone = "Admin\Entity\Usuariotelefone";
        $this->entityEndereco = "Admin\Entity\Usuarioendereco";
        $this->form = "Admin\Form\Usuario";
        $this->service = "Admin\Service\Usuario";
        $this->serviceSocial = "AdminSocial\Service\UsuarioSocial";
        $this->controller = "conta";
        $this->route = "admin-admin/default";
        $this->nome = "Cadastro";
        $this->admin = false;
        $this->useSocial = $this->getServiceLocator()->get('config')["usaprovedorsocial"];
    }
    
    public function indexAction() {
        $usuario = $this->getEm()
                ->find($this->entity,$this->usuarioLogado()->getId());
        return new ViewModel(array('usuario' => $usuario));
    }

    public function newAction(){
        return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
    }

    public function deletesocialAction(){
        if($this->useSocial){
            $form = new SocialForm("social", $this->getEm());
            $request = $this->getRequest();

            if($request->isPost())
            {
                $service = $this->getServiceLocator()->get($this->serviceSocial);
                if($service->delete($request->getPost('oauth_uid'))){
                    $this->flashMessenger()
                                ->addWarningMessage(strtoupper($this->nome)." apagado com sucesso.");
                }else{
                    $this->flashMessenger()
                                ->addErrorMessage(strtoupper($this->nome)." não foi apagado.");
                }
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            }
            
            $repository = $this->getEm()->getRepository($this->entitySocial);
            $entity = $repository->findByOauthUid($this->params()->fromRoute('id',0));
            if(count($entity)==1)
                $form->setData($entity[0]->toArray());
            $form->removerCampos(["nomeexibicao", "aceite"]);
            return new ViewModel(array('form'=>$form));
        }
    }

    public function imagemperfilAction(){
        $form = new ImageForm("perfil");
        $tempFile = null;

        $prg = $this->fileprg($form);
        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $data = $form->getData();
                // Form is valid, save the form!

                $foto = $data['imagem']['tmp_name'];
                $dirPerfil = $_SERVER["DOCUMENT_ROOT"].'/img/fotos/perfil/';
                $fotoLargura = $fotoAltura = 500;
                $fotoQualidade = 80;
                $fotoInterlace = 1;
                
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
                imagejpeg($imgP, $dirPerfil.$this->usuarioLogado()->getId().".jpg", $fotoQualidade);

                unlink($foto);
                $this->flashMessenger()
                                ->addSuccessMessage("Imagem de perfil atualizada.");
                return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
            } else {
                // Form not valid, but file uploads might be valid...
                // Get the temporary file information to show the user in the view
                $fileErrors = $form->get('imagem')->getMessages();
                if (empty($fileErrors)) {
                    $tempFile = $form->get('imagem')->getValue();
                }
                $this->flashMessenger()
                                ->addErrorMessage("Erro ao atualizar a imagem de perfil.");
            }
        }

        return array(
            'form'     => $form,
            'tempFile' => $tempFile,
        );
    }

    public function telefoneAction(){
        $this->nome = "Telefone";
        $form = new \Admin\Form\Telefone("usuariotelefone");
        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();
            $form->setData($post);
            if($form->isValid($post))
            {
                $service = $this->getServiceLocator()->get($this->service);
                $result = $service->telefone($post->toArray());
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
        $array = ['usuario' => $this->getId()];
        $repository = $this->getEm()->getRepository($this->entityTelefone);
        $entity = $repository->findOneById($this->params()->fromRoute('id',0));
        if(
            $entity 
            && $entity->getUsuario()->getId() == $this->getId()
        ){
            $array = $entity->toArray();
            $array['usuario'] = $entity->getUsuario()->getId();
        }
        $form->setData($array);
        return new ViewModel(array('form'=>$form));
    }
    
    public function deletetelefoneAction(){
        $this->nome = "Telefone";
            $form = new \Admin\Form\Telefone("usuariotelefone");
        $request = $this->getRequest();

        if($request->isPost())
        {
            $service = $this->getServiceLocator()->get($this->service);
            if($service->deletetelefone($request->getPost('id'))){
                $this->flashMessenger()
                            ->addWarningMessage(strtoupper($this->nome)." apagado com sucesso.");
            }else{
                $this->flashMessenger()
                            ->addErrorMessage(strtoupper($this->nome)." não foi apagado.");
            }
            return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
        }
        
        $repository = $this->getEm()->getRepository($this->entityTelefone);
        $telefone = $repository->findOneById($this->params()->fromRoute('id',0));
        $array = $telefone->toArray();
        unset($array['usuario']);
        $form->setData($array);
        return new ViewModel(array('form'=>$form));
    }

    public function enderecoAction(){
        $this->nome = "Endereco";
        $form = new \Admin\Form\Endereco("usuarioendereco");
        $request = $this->getRequest();
        if($request->isPost())
        {
            $post = $request->getPost();
            $form->setData($post);
            if($form->isValid($post))
            {
                $service = $this->getServiceLocator()->get($this->service);
                $result = $service->endereco($post->toArray());
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
        $array = ['usuario' => $this->getId()];
        $repository = $this->getEm()->getRepository($this->entityEndereco);
        $entity = $repository->findOneById($this->params()->fromRoute('id',0));
        if(
            $entity 
            && $entity->getUsuario()->getId() == $this->getId()
        ){
            $array = $entity->toArray();
            $array['pais'] = "Brasil";
            $array['estado'] = $entity->getEndereco()->getCity()->getState()->getInitials();
            $array['cidade'] = $entity->getEndereco()->getCity()->getName();
            $array['bairro'] = $entity->getEndereco()->getDistrict()->getName();
            $array['endereco'] = $entity->getEndereco()->getAddress();
            $array['cep'] = $entity->getEndereco()->getPostalCode();
            $array['usuario'] = $entity->getUsuario()->getId();
        }
        $form->setData($array);
        return new ViewModel(array('form'=>$form));
    }
    
    public function deleteenderecoAction(){
        $this->nome = "Endereço";
            $form = new \Admin\Form\Endereco("usuarioendereco");
        $request = $this->getRequest();

        if($request->isPost())
        {
            $service = $this->getServiceLocator()->get($this->service);
            if($service->deleteendereco($request->getPost('id'))){
                $this->flashMessenger()
                            ->addWarningMessage(strtoupper($this->nome)." apagado com sucesso.");
            }else{
                $this->flashMessenger()
                            ->addErrorMessage(strtoupper($this->nome)." não foi apagado.");
            }
            return $this->redirect()->toRoute($this->route,array('controller'=>$this->controller));
        }

        $repository = $this->getEm()->getRepository($this->entityEndereco);
        $entity = $repository->findOneById($this->params()->fromRoute('id',0));
        $array = $entity->toArray();
        unset($array["endereco"]);
        unset($array["usuario"]);
        $array['pais'] = "Brasil";
        $array['estado'] = $entity->getEndereco()->getCity()->getState()->getInitials();
        $array['cidade'] = $entity->getEndereco()->getCity()->getName();
        $array['bairro'] = $entity->getEndereco()->getDistrict()->getName();
        $array['endereco'] = $entity->getEndereco()->getAddress();
        $array['cep'] = $entity->getEndereco()->getPostalCode();
        $form->setData($array);
        return new ViewModel(array('form'=>$form));
    }

    public function passwordAction()
    {
        return parent::passwordAction();
    }

    protected $useSocial = false;
}
