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

class Endereco extends AbstractForm
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setInputFilter(new EnderecoFilter());
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

        $cep = new Element\Text("cep");
        $cep->setLabel("CEP: ");
        $cep->setAttribute("id", "cep");
        $cep->setAttribute("readonly", "readonly");
        $this->add($cep);

        $pais = new Element\Text("pais");
        $pais->setLabel("País: ");
        $pais->setAttribute("id", "pais");
        $pais->setAttribute("readonly", "readonly");;
        $this->add($pais);

        $cidade = new Element\Text("cidade");
        $cidade->setLabel("Cidade: ");
        $cidade->setAttribute("id", "cidade");
        $cidade->setAttribute("readonly", "readonly");
        $this->add($cidade);

        $estado = new Element\Text("estado");
        $estado->setLabel("Estado: ");
        $estado->setAttribute("id", "estado");
        $estado->setAttribute("readonly", "readonly");
        $this->add($estado);
        
        $bairro = new Element\Text("bairro");
        $bairro->setLabel("Bairro: ");
        $bairro->setAttribute("id", "bairro");
        $bairro->setAttribute("readonly", "readonly");
        $this->add($bairro);

        $endereco = new Element\Text("endereco");
        $endereco->setLabel("Endereço: ");
        $endereco->setAttribute("id", "endereco");
        $endereco->setAttribute("readonly", "readonly");
        $this->add($endereco);

        $numero = new Element\Text("numero");
        $numero->setLabel("Número: ");
        $this->add($numero);

        $complemento = new Element\Text("complemento");
        $complemento->setLabel("Complemento: ");
        $this->add($complemento);

        $referencia = new Element\Text("referencia");
        $referencia->setLabel("Referência: ");
        $this->add($referencia);

        $nome = new Element\Text("nome");
        $nome->setLabel("Nome da localidade: ");
        $this->add($nome);

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
