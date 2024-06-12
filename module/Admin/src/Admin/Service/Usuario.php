<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Admin\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator,
    Zend\Authentication\Storage\Session as SessionStorage;
use Base\Mail\Mail,
    Base\Service\AbstractService;


class Usuario extends AbstractService
{

    
    public function __construct(ServiceManager $sm, $extended=false) 
    {
        parent::__construct($sm);
        if(!$extended){
            $this->cfg = $sm->get('config');
            $this->view = $sm->get('View');
            $this->entity = "Admin\Entity\Usuario";
            $this->entityes = [
                "AdminSocial\Entity\UsuarioSocial",
                $this->entity
            ];
            $this->entityTelefone = "Admin\Entity\Usuariotelefone";
            $this->entityEndereco = "Admin\Entity\Usuarioendereco";
            $this->entityAddressSearchs = "Admin\Entity\AddressSearchs";
        }
    }
    
    public function insert(array $data) {
        $this->email = $this->notificaEmail($data);
        $existe = $this->existeEmail($data["email"],$this->entityes);
        if($existe===false){
            $existe = $this->existeCPF($data["cpf"]);
        }
        if($existe!==false){
            return $existe;
        }
        if(empty($data['senha']))
            $data["senha"] = $data["security"];

        $entity = new $this->entity($data);
        $repo = $this->em->getRepository("Acl\Entity\Role");

        if(!empty($data["role"])){
            $entity->addRole($repo->find($data["role"]));
        }else{
            $entity->addRole($repo->findByNome("membro")[0]);
        }
        $entity = $this->persistTransactional($entity);
        if($entity)
        {
            $this->logStatus(
                "Usuario - adicionado",
                "Novo usuário cadastrado - ID: ".$entity->getId()
            );
            $this->enviaEmail(
                $entity,
                "Confirmação de Cadastro",
                'add-user'
            );
            // $this->fm->setNamespace('Admin')
            //         ->addSuccessMessage("Usuário cadastrado com sucesso.");
        }
        return $entity;
    }
    
    public function activate($key)
    {
        $repo = $this->em->getRepository($this->entity);
        
        $entity = $repo->findOneByActivationkey($key);
        if($entity)
        {
            if($entity->getActivate()){
                return "ativado";
            }

            $entity->setActivate(true);
            $this->em->persist($entity);
            $this->em->flush();

            $this->logStatus(
                "Usuario - ativado",
                "Usuário ativado - ID: ".$entity->getId()
            );
            
            return $entity;
        }
        return false;
    }
    
    public function update(array $data)
    {
        if(!$data['id'])
            return false;
        $this->email = $this->notificaEmail($data);
        $entity = $this->em->getReference($this->entity, $data['id']);
        if($entity)
            if($entity->getRole()->count()==1)
                if($entity->getRole()[0]->getId()<1)
                    return false;
                    
        $emailAlterado = false;
        $emailAntigo = $entity->getEmail();
        if($emailAntigo!=$data["email"]){
            $emailAlterado = true;
            $existe = $this->existeEmail($data["email"],$this->entityes);
            if($existe!==false){
                return $existe;
            }
        }
        $senhaAlterada = true;
        $data['senha'] = $data['novasenha'];
        if(empty($data['senha'])){
            unset($data['senha']);
            $senhaAlterada = false;
        }
        (new Hydrator\ClassMethods())->hydrate($data, $entity);
        if($entity){
            $papel = "";
            if(!empty($data["role"])){
                if($entity->getRole()->count()>0){
                    // Regra para apenas um papel
                    $entity->removeRole($entity->getRole()[0]);
                }
                $role = $this->em->getReference("Acl\Entity\Role", $data["role"]);
                $papel = "\n Papel: ".$role->getNome();
                $entity->addRole($role);
            }
            if(!empty($data["datanasci"]) || !empty($data["sexo"])){
                if(empty($data['datanasci']))
                    unset($data['datanasci']);
                else{
                    $data['datanasci'] = \DateTime::createFromFormat("Y-m-d", $data['datanasci']);
                }
                $dados = $entity->getDados();
                if($dados == null)
                    $dados = new \Admin\Entity\Usuariodados($data);
                else{
                    $dados->setDatanasci($data['datanasci']);
                    $dados->setSexo($data['sexo']);
                }
                $dados->setUsuario($entity);
                $entity->setDados($dados);
            }
            if($emailAlterado){
                $entity->setActivate(false);
            }
            $this->em->persist($entity);
            $this->em->flush();
            $usuarioLogado = $this->usuarioLogado();
            if($usuarioLogado){
                if($entity->getId() == $usuarioLogado->getId()){
                    $sessionStorage = new SessionStorage("Admin");
                    $sessionStorage->write($entity, null);
                }
            }
            if($emailAlterado){
                $this->logStatus(
                    "Usuario - modificado",
                    "Usuário modificado - ID: ".$entity->getId()." \n E-mail antigo: ".$emailAntigo." \n
                    E-mail novo: ".$entity->getEmail().$papel
                );
                $this->enviaEmail(
                    $entity,
                    "E-mail modificado na conta",
                    'emailalterado',
                    [$emailAntigo]
                );
                return "email";
            }elseif($senhaAlterada){
                $this->logStatus(
                    "Usuario - modificado",
                    "Usuário modificado - ID: ".$entity->getId()." \n Senha alterada e conta ativa".$papel
                );
                $this->enviaEmail(
                    $entity,
                    "Senha alterada e conta ativa",
                    'activate'
                );
            }else{
                $this->logStatus(
                    "Usuario - modificado",
                    "Usuário modificado - ID: ".$entity->getId().$papel
                );
            }
        }
        return $entity;
    }
    
    public function delete($id)
    {
        $entity = $this->em->getReference($this->entity, $id);
        if($entity)
        {
            if($entity->getRole()->count()==1)
                if($entity->getRole()[0]->getId()<1)
                    return false;
            $email = $entity->getEmail();
            $this->em->remove($entity);
            $this->em->flush();
            $this->logStatus(
                "Usuario - apagado",
                "Usuário apagado - ID: ".$id."\n
                E-mail: ".$email
            );
            return $id;
        }
        return $entity;
    }

    public function password(array $data){
        $id = $data["id"];
        $senha = $data["senha"];
        $repository = $this->em->getRepository($this->entity);
        $user = $repository->findByIdAndPassword($id, $senha);
        if($user && senha != $data['novasenha']){
            $senha = $data['novasenha'];
            $user->setSenha($senha);
            $this->em->persist($user);
            $this->em->flush();
            $this->logStatus(
                "Usuario - senha alterada",
                "Usuário alterado - ID: ".$id."\n
                alterado por: ".$this->usuarioLogado()->getId()
            );
            $this->enviaEmail(
                $user,
                "Senha alterada e conta ativa",
                'activate'
            );
        }
        return $user;
    }

    public function telefone(array $data){
        $telefone = null;
        if(!empty($data["id"]))
            $telefone = $this->em->getReference($this->entityTelefone, $data['id']);
        $usuarioId = $data["usuario"];
        unset($data["usuario"]);
        if(!$telefone){
            $telefone = new $this->entityTelefone($data);
            $telefone->setUsuario($this->em->getReference($this->entity, $usuarioId));
        }else{
            (new Hydrator\ClassMethods())->hydrate($data, $telefone);
        }
        $this->em->persist($telefone);
        $this->em->flush();

        $usuarioLogado = $this->usuarioLogado();
        if($usuarioLogado){
            if($usuarioId == $usuarioLogado->getId()){
                $sessionStorage = new SessionStorage("Admin");
                $sessionStorage->write($telefone->getUsuario(), null);
            }
        }

        $papel = $telefone->getUsuario()->getRole()[0]->getNome();
        $this->logStatus(
            "Telefone - modificado",
            "Usuário modificado - ID: ".$usuarioId." ".$papel . " - telefone: " . $data["telefone"]
        );
        return $telefone;
    }
    
    public function deletetelefone($id)
    {
        $telefone = $this->em->getReference($this->entityTelefone, $id);
        if($telefone)
        {
            $numeroTelefone = $telefone->getTelefone();
            $usuario = $telefone->getUsuario()->getId();
            $this->em->remove($telefone);
            $this->em->flush();
            $this->logStatus(
                "Telefone - apagado",
                "Telefone apagado - ID - TEL: ".$id." - ".$numeroTelefone."\n
                usuario: ".$usuario
            );
            return $id;
        }
        return $telefone;
    }

    public function endereco(array $data){
        $endereco = null;
        if(!empty($data["id"]))
            $endereco = $this->em->getReference($this->entityEndereco, $data['id']);
        $usuarioId = $data["usuario"];
        unset($data["usuario"]);
        unset($data["endereco"]);

        if(!$endereco){
            $endereco = new $this->entityEndereco($data);
        }

        $addressSearchs = $this->em->getRepository($this->entityAddressSearchs)->findOneByPostalCode($data["cep"]);
        if($addressSearchs)
            $endereco->setEndereco($addressSearchs);
        $endereco->setUsuario($this->em->getReference($this->entity, $usuarioId));
        $endereco->setNumero($data["numero"])
            ->setComplemento($data["complemento"])
            ->setReferencia($data["referencia"])
            ->setNome($data["nome"])
            ->setExibir($data["exibir"]);
        $this->em->persist($endereco);
        $this->em->flush();

        $usuarioLogado = $this->usuarioLogado();
        if($usuarioLogado){
            if($usuarioId == $usuarioLogado->getId()){
                $sessionStorage = new SessionStorage("Admin");
                $sessionStorage->write($endereco->getUsuario(), null);
            }
        }

        $papel = $endereco->getUsuario()->getRole()[0]->getNome();
        $this->logStatus(
            "Endereco - modificado",
            "Usuário modificado - ID: ".$usuarioId." ".$papel . " - endereco: " . $data["endereco"]
        );
        return $endereco;
    }
    
    public function deleteendereco($id)
    {
        $endereco = $this->em->getReference($this->entityEndereco, $id);
        if($endereco)
        {
            $enderecoNome = $endereco->getEndereco()->getAddress();
            $enderecoCEP = $endereco->getEndereco()->getPostalCode();
            $usuario = $endereco->getUsuario()->getId();
            $this->em->remove($endereco);
            $this->em->flush();
            $this->logStatus(
                "Endereco - apagado",
                "Endereco apagado - CEP " . $enderecoCEP . " - Endereço: " . $enderecoNome . "\n
                usuario: ".$usuario
            );
            return $id;
        }
        return $endereco;
    }

    public function enviaEmail($entity, $assunto, $view, $ccs=[]){
        if($this->email){
            $emailConfig = $this->cfg['email']['connection']['params'];
            
            $emailData = array(
                'nome' => $entity->getNomeexibicao(),
                'activationKey' => $entity->getActivationkey(),
                'email' => $entity->getEmail()
            );

            $mail = new Mail($emailConfig, $this->view, $view);
            foreach($ccs as $cc){
                $mail->get()->addCC($cc);
            }
            $mail->setData($emailData)
                ->setTo($entity->getEmail())
                ->setToName($emailData['nome'])
                ->setSubject($assunto)
                ->prepare()
                ->send();
        }
    }

    public function persistTransactional($entity)
    {
        if($entity){
            $this->entityObject = $entity;
            $this->em->transactional(function($em)
            {
                $sql ='select usuarioID() as id';
                $stmt = $this->em->getConnection()->prepare($sql);
                $stmt->execute();
                $arr = $stmt->fetch();
                $this->entityObject->setId($arr["id"]);
                $em->persist($this->entityObject);
                $em->flush();
            });
            unset($this->entityObject);
            return $entity;
        }
        return false;
    }
    
    public function notificaEmail($data){
        if(!empty($data["notifica"]))
            $this->email = ($data["notifica"])?true:false;
        return $this->email;
    }

    public function existeEmail($email, $entityes = []){
        $logado = $this->usuarioLogado();
        $i=0;
        $achou = false;
        while($i<count($entityes) && !$achou){
            $entity = $entityes[$i++];
            $repo = $this->em->getRepository($entity);
            $entity = $repo->findOneByEmail($email);
                
            if($entity){
                if($logado){
                    if(
                        $logado->getEmail()!=$email
                    ){
                        if(
                            $entity->getByEmail($email)->count()>0
                        ){
                            $achou = true;
                            return "E-mail já possuí cadastro.";
                        }
                    }
                }else{
                    $achou = true;
                    return "E-mail já possuí cadastro.";
                }
            }
        }
        return $achou;
    }

    public function existeCPF($cpf){
        $logado = $this->usuarioLogado();
        $achou = false;
        $entity = $this->entity;
        $repo = $this->em->getRepository($entity);
        $entity = $repo->findOneByCpf($cpf);
                
        if($entity){
            $achou = true;
            return "CPF já utilizado em cadastro.";
        }
        return $achou;
    }

    // protected $fm;
    protected $cfg;
    protected $view;
    protected $email=true;
    protected $entity;
    protected $entityes;
    protected $entityTelefone;
    protected $entityEndereco;
    protected $entityAddressSearchs;
}
