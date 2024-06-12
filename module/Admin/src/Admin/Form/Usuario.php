<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Form;

use Doctrine\ORM\EntityManager;
use Base\Form\AbstractForm;
use Zend\Form\Element;
use Admin\Enum;

class Usuario extends AbstractForm
{
    public function __construct($name = null, array $roles = null, EntityManager $em = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setInputFilter(new UsuarioFilter($em));
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-aligned');

        $id = new Element\Hidden('id');
        $this->add($id);

        $nome = new Element\Text("nomeexibicao");
        $nome->setLabel("Nome de Exibição: ")
                ->setAttribute('placeholder', 'Entre com o nome a exibir');
        $this->add($nome);

        $nome = new Element\Text("nome");
        $nome->setLabel("Nome: ")
                ->setAttribute('placeholder', 'Entre com o nome');
        $this->add($nome);

        $nome = new Element\Text("snome");
        $nome->setLabel("Sobrenome: ")
                ->setAttribute('placeholder', 'Entre com o sobrenome');
        $this->add($nome);

        $data = new Element\Date("datanasci");
        $data->setLabel("Data de nascimento: ")
                ->setOptions([
                        'format' => 'Y-m-d'
                ]);
        $this->add($data);

        $sexos = array_flip(Enum\Sexo::toArray());
        $sexo = new Element\Select("sexo");
        $sexo->setLabel("Sexo: ")
                ->setOptions(
                        array(
                                'value_options' =>  $sexos
                        )
                );
        $this->add($sexo);

        $cpf = new Element\Text("cpf");
        $cpf->setLabel("CPF: ")
                ->setAttribute('placeholder', 'Entre com o CPF');
        $this->add($cpf);

        $email = new Element\Email("email");
        $email->setLabel("E-mail: ")
                ->setAttribute('placeholder', 'Entre com o E-mail');
        $this->add($email);

        $role = new Element\Select();
        $role->setLabel("Papel: ")
                ->setName("role")
                ->setOptions(array('value_options' => $roles))
                ->setValue(Enum\Papel::Membro());
        $this->add($role);

        $isActive = new Element\Checkbox("activate");
        $isActive->setLabel("Ativo?: ");
        $this->add($isActive);

        $password = new Element\Password("senha");
        $password->setLabel("Senha: ")
                ->setAttribute('placeholder', 'Entre com a senha');
        $this->add($password);

        
        $newpassword = new Element\Password("novasenha");
        $newpassword->setLabel("Nova senha: ")
                ->setAttribute('placeholder', 'Entre com a nova senha');
        $this->add($newpassword);

        $confirmation = new Element\Password("confirmacao");
        $confirmation->setLabel("Redigite a senha: ")
                ->setAttribute('placeholder', 'Redigite a nova senha');
        $this->add($confirmation);

        $termos = new Element\Checkbox("aceite");
        $termos->setOptions(Array(
                'checked_value' => 1,
                'unchecked_value' => 'no'
        ));
        $termos->setLabel(" Aceito e confirmo ter lido os <a href=\"".\Base\Mib\URL::getInstance()->getDir()."termosdeservico/\">termos de serviço</a>. ");
        $termos->setLabelOptions(array('disable_html_escape'=>true, "label_position"=>"label_prepend"));
        $this->add($termos);

        $csrf = new Element\Csrf("security");
        $csrf->getCsrfValidator()->setMessages(array("notSame"=>"O formulário veio de uma página inesperada. Favor tentar novamente."));
        $this->add($csrf);

        $this->add([
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => [
                'value' => 'Salvar',
                'class' => 'pure-button pure-button-primary'
            ]
        ]);
    }
}
