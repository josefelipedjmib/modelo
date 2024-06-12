<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class Image extends Form
{

    public function __construct($name = null, $options = array()) {
        parent::__construct($name, $options);
        $this->setInputFilter(new ImageFilter());
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-stacked');

        $imagem = new Element\File("imagem");
        $imagem->setLabel("Carregar Imagem: ")
                ->setAttribute('placeholder','Escolha a imagem');
        $this->add($imagem);
        
        $csrf = new Element\Csrf("security");
        $csrf->getCsrfValidator()->setMessages(array("notSame"=>"O formulário veio de uma página inesperada. Favor tentar novamente."));
        $this->add($csrf);
        
        $this->add(array(
            'name' => 'submit',
            'type'=>'Zend\Form\Element\Submit',
            'attributes' => array(
                'value'=>'Enviar',
                'class' => 'pure-button pure-button-primary'
            )
        ));
                
       
    }
    
}
