<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mail;

use PHPMailer;
use Zend\View\Model\ViewModel;
use Base\Mib\Pagina;

class Mail{


    public function __construct($emailcfg, $view, $page){
        $this->mail = new PHPMailer();
        $this->config = $emailcfg;
        $this->view = $view;
        $this->page = $page;
        //Novo
        $this->mail->IsSMTP();
        $this->mail->CharSet="UTF-8";
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $this->mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $this->mail->Debugoutput = 'html';
    }

    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }

    public function setTo($to){
        $this->to = $to;
        return $this;
    }

    public function setToName($toName){
        $this->toName = $toName;
        return $this;
    }

    public function setReplyTo($replyTo){
        $this->replyTo = $replyTo;
        return $this;
    }

    public function setReplyToName($replyToName){
        $this->replyToName = $replyToName;
        return $this;
    }

    public function setFrom($from){
        $this->from = $from;
        return $this;
    }

    public function setFromName($fromName){
        $this->fromName = $fromName;
        return $this;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function get(){
        return $this->mail;
    }

    public function renderView($page, array $data){
        $model = new ViewModel;
        $model->setTemplate("mailer/{$page}.phtml");
        $model->setOption('has_parent',true);
        $model->setVariables($data);
        
        return $this->view->render($model);
    }

    public function prepare(){
        $strMensagem = $this->renderView($this->page, $this->data);

        //Conexão com Servidor
        $this->mail->SMTPSecure = $this->config['smtpsecure'];
        $this->mail->SMTPAuth = $this->config['smtpauth'];
        $this->mail->Port = $this->config['port'];

        //Host conforme servidor necessita
        //para outras hospedagens 'smtp.pqt.com.br';
        //Para cPanel: 'localhost';
        //Para Plesk 11: 'smtp.dominio.com.br';
        //Para Plesk 11.5 Linux: 'tls://localhost';
        //Para Plesk 11.5 Windows: 'localhost';
        $this->mail->Host = $this->config['host'];
        $this->mail->Username = $this->config['user'];
        $this->mail->Password = $this->config['password'];

        //Quem envia
        $this->mail->setFrom($this->from, $this->fromName);
        //Recebe de
        //$this->mail->From = 'ilha@pqt.com.br';
        //$this->mail->FromName = 'José Felipe Teste';
        $this->mail->AddReplyTo($this->replyTo, $this->replyToName);
        //Quem recebe
        $this->mail->AddAddress($this->to, $this->toName);

        $this->mail->IsHTML(true);
        $this->mail->Subject    = "José Felipe Teste - ".$this->subject;
        $this->mail->Body    = $strMensagem;
        $this->mail->AltBody = Pagina::htmlParaTextoPlano($strMensagem);
        // set word wrap to 50 characters
        $this->mail->WordWrap = 50;
        
        return $this;
    }

    public function send(){


        // Envio da Mensagem
        $enviado = $this->mail->Send();

        // Limpa os destinatários e os anexos
        $this->mail->ClearAllRecipients();
        $this->mail->ClearAttachments();

        return $enviado;

    }
    


    protected $view;
    protected $config;
    protected $mail;
    protected $subject;
    protected $to = "josefelipe@rioeduca.net";
    protected $toName = "SME - CIT - Teste";
    protected $replyTo = "josefelipe@rioeduca.net";
    protected $replyToName = "SME - CIT - Teste";
    protected $from = "josefelipe@rioeduca.net";
    protected $fromName = "SME - CIT - Teste";
    protected $data;
    protected $page;

}
