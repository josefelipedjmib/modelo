<?php

namespace Acl\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class Resource extends Form 
{    
    public function __construct($name = null) 
    {
        parent::__construct($name);
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-aligned');

        $id = new Element\Hidden('id');
        $this->add($id);

        $nome = new Element\Text("nome");
        $nome->setLabel("Nome: ")
                ->setAttribute('placeholder', "Entre com o nome");
        $this->add($nome);
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'pure-button pure-button-primary'
            )
        ));
    }

}
