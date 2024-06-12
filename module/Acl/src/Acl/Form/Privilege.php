<?php

namespace Acl\Form;

use Zend\Form\Form,
    Zend\Form\Element;

class Privilege extends Form {
    
    protected $roles;
    protected $resources;

    public function __construct($name = null, array $roles = null, array $resources = null) {
        parent::__construct($name);
        $this->roles = $roles;
        $this->resources = $resources;
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'pure-form pure-form-aligned');

        $id = new Element\Hidden('id');
        $this->add($id);

        $nome = new Element\Text("nome");
        $nome->setLabel("Nome: ")
                ->setAttribute('placeholder', "Entre com o nome");
        $this->add($nome);
        
        $role = new Element\Select();
        $role->setLabel("Papel: ")
                ->setName("role")
                ->setOptions(array('value_options' => $roles));
        $this->add($role);
        
        $resource = new Element\Select();
        $resource->setLabel("Recurso: ")
                ->setName("resource")
                ->setOptions(array('value_options' => $resources));
        $this->add($resource);
        
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
