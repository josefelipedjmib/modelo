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
use Base\Mib\DataHora;

class Telefone extends AbstractForm
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setInputFilter(new TelefoneFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-aligned');
        $recursos = explode("-",$name);
        $recursoNome = (count($recursos) > 1)
                    ? $recursos[0]
                    : "usuario";

        $id = new Element\Hidden('id');
        $this->add($id);
        
        $recurso = new Element\Hidden($recursoNome);
        $this->add($recurso);

        $telefone = new Element\Text("telefone");
        $telefone->setLabel("Telefone (ddd + número): ")
                ->setAttribute('placeholder', '(xx) xxxxx-xxxx');
        $this->add($telefone);
        
        $isMovel = new Element\Checkbox("movel");
        $isMovel->setLabel("É celular?: ");
        $this->add($isMovel);
        
        $ewhatsapp = new Element\Checkbox("ewhatsapp");
        $ewhatsapp->setLabel("É WhatsApp?: ");
        $this->add($ewhatsapp);
        
        $etelegram = new Element\Checkbox("etelegram");
        $etelegram->setLabel("É Telegram?: ");
        $this->add($etelegram);

        $csrf = new Element\Csrf("security");
        $csrf->getCsrfValidator()
                ->setMessages(array("notSame"=>"O formulário veio de uma página inesperada. Favor tentar novamente."))
                ->setTimeout(1200);
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
