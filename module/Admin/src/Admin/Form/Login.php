<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Form;

use Base\Form\AbstractForm,
    Zend\Form\Element;

class Login  extends AbstractForm
{

    public function __construct($name = null, $options = array()) {
        parent::__construct($name, $options);
        $this->setInputFilter(new LoginFilter());
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-stacked');

        $cpfid = new Element\Hidden('cpfid');
        $this->add($cpfid);

        $cpf = new Element\Text("cpf");
        $cpf->setLabel("CPF: ")
                ->setAttribute('placeholder','Entre com o CPF');
        $this->add($cpf);
        
        $email = new Element\Text("email");
        $email->setLabel("E-mail: ")
                ->setAttribute('placeholder','Entre com o Email');
        $this->add($email);
       
        $password = new Element\Password("password");
        $password->setLabel("Senha: ")
                ->setAttribute('placeholder','Entre com a senha');
        $this->add($password);
        
        $csrf = new Element\Csrf("security");
        $csrf->getCsrfValidator()->setMessages(array("notSame"=>"O formulário veio de uma página inesperada. Favor tentar novamente."));
        $this->add($csrf);
        
        $this->add(array(
            'name' => 'submit',
            'type'=>'Zend\Form\Element\Submit',
            'attributes' => array(
                'value'=>'Entrar',
                'class' => 'pure-button pure-button-primary'
            )
        ));
                
       
    }
    
}
