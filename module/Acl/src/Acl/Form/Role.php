<?php

namespace Acl\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class Role extends Form {
    
    protected $parent;

    public function __construct($name = null, array $parent = null) {
        parent::__construct($name);
        $this->parent = $parent;

        
        $this->setInputFilter(new RoleFilter());
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-aligned');

        $id = new Element\Hidden('id');
        $this->add($id);

        $nome = new Element\Text("nome");
        $nome->setLabel("Nome: ")
                ->setAttribute('placeholder', "Entre com o nome");
        $this->add($nome);
        
        //$allParent = array(0=>'Nenhum') + $this->parent;
        $allParent = $this->parent;
        $parent = new Element\Select();
        $parent->setLabel("Herda: ")
                ->setName("parent")
                ->setOptions(array(
                    'value_options' => $allParent,
                    'empty_option' => 'Nenhum'
                ));
        $this->add($parent);
        
        $isAdmin = new Element\Checkbox("isAdmin");
        $isAdmin->setLabel("Admin?: ");
        $this->add($isAdmin);
        
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
