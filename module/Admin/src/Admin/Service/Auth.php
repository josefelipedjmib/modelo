<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Admin\Service;

use Zend\ServiceManager\ServiceManager,
    Zend\Authentication\Storage\Session as SessionStorage,
    Zend\Authentication\AuthenticationService;

use Base\Mib\URL,
    Base\Mib\Texto,
    Base\Mail\Mail,
    Doctrine\ORM\EntityManager;


class Auth
{

    
    public function __construct(ServiceManager $sm) 
    {
            $this->sm = $sm;
            $this->em = $this->sm->get('Doctrine\ORM\EntityManager');
            $this->cfg = $sm->get('config');
            $this->view = $sm->get('View');
            $this->entity = "Admin\Entity\Usuario";
    }

    public function autenticar($authAdapter)
    {
        // Criando Storage para gravar sessão da authtenticação
        $sessionStorage = new SessionStorage("Admin");
        
        $auth = new AuthenticationService;
        $auth->setStorage($sessionStorage); // Definindo o SessionStorage para a auth
        
        $result = $auth->authenticate($authAdapter);
        if($result->isValid())
        {
            if($auth->getIdentity()['user']->getActivate()){
                $sessionStorage->write($auth->getIdentity()['user'],null);
                return 'ok';
            }
            return 'desativado';
        }
        return 'invalido';
    }

    public function preparaAutenticacaoCPF($cpf){
        $usuario = $this->em->getRepository($this->entity)->findOneByCpf($cpf);
        if($usuario)
        {
            $this->senha = strtoupper(Texto::geraId(10));
            $enviado = $this->enviaEmail($usuario, 'Pedido de login por CPF no '.$this->cfg['sistema']['nome'], 'senhalogin');
            if($enviado){
                $usuario->setSenha($this->senha);
                $this->em->persist($usuario);
                $this->em->flush();
                $this->logStatus(
                    "Usuario - senha alterada",
                    "Usuário alterado - ID: ".$id."\n
                    alterado por: 000001"
                );
                return "ok";
            }
            return "error";
        }
        return "inexistente";
    }

    private function enviaEmail($entity, $assunto, $view, $ccs=[]){
        if($this->email){
            $emailConfig = $this->cfg['email']['connection']['params'];
            $emailData = array(
                "senha" => $this->senha,
            );

            $mail = new Mail($emailConfig, $this->view, $view);
            foreach($ccs as $cc){
                $mail->get()->addCC($cc);
            }
            return $mail->setData($emailData)
                ->setTo($entity->getEmail())
                ->setToName($entity->getNomeexibicao())
                ->setSubject($assunto)
                ->prepare()
                ->send();
        }
    }

    private function logStatus($acao, $status){
        $usuario = $this->em->find(
            $this->entity,
            '000001' //Usuário de Sistema
        );
        $registro = new \Admin\Entity\Logsystem();
        $registro->setAcao($acao);
        $registro->setStatus($status);
        $registro->setUsuario($usuario);
        $ip = \Base\Mib\URL::getInstance()->getIPTratado();
        $registro->setIpa($ip[0]);
        $registro->setIpb($ip[1]);
        $registro->setIpc($ip[2]);
        $registro->setIpd($ip[3]);
        $this->em->flush();
    }

    protected $sm;
    protected $em;
    protected $cfg;
    protected $view;
    protected $email=true;
    protected $senha;
    protected $entity;
    
}
