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
use Zend\InputFilter\InputFilter;
use Zend\Validator,
    Base\Form\Validator\Cpf;

class UsuarioFilter extends InputFilter
{

    public function __construct(EntityManager $em = null)
    {

        $this->add(array(
            'name' => 'nome',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty', 'options' => array('messages' => array('isEmpty' => 'Não pode estar em branco.'))),
            ),
        ));

        $this->add(array(
            'name' => 'nomeexibicao',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Não pode estar em branco.',
                        ),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 2,
                        'max' => 30,
                        'messages' => array(
                            Validator\StringLength::TOO_LONG => 'Nome não pode ter mais de %max% caracteres.',
                            Validator\StringLength::TOO_SHORT => 'Tem que ser no mínimo %min% caracteres.',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'cpf',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Não pode estar em branco.',
                        ),
                    ),
                ),
                array(
                    'name' => Cpf::class
                ),
            ),
        ));

        $this->add(array(
            'name' => 'datanasci',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            )
        ));

        $validator = new Validator\EmailAddress;
        $validator->setOptions(array('domain' => false));
        $validator->setMessages(array(
            Validator\EmailAddress::INVALID_FORMAT => 'Por favor verifique o seu e-mail, ele está incorreto.',
        ));

        $message = "E-mail não é válido!";

        $validator = new Validator\EmailAddress;
        $validator->setOptions(array('domain' => false));
        $validator->setMessages(array(
            'emailAddressInvalidFormat' => 'Por favor verifique o seu e-mail, ele está incorreto.',
            'emailAddressLengthExceeded' => 'Excedeu o tamanho permitido de 100 caracteres.',
            Validator\EmailAddress::DOT_ATOM => "'%localPart%' não segue os padrões de e-mail.",
            Validator\EmailAddress::QUOTED_STRING => "'%localPart%' não pode conter caracteres especiais.",
            Validator\EmailAddress::INVALID_LOCAL_PART => "'%localPart%' não é um e-mail válido.",
            Validator\EmailAddress::INVALID => $message,
            Validator\EmailAddress::INVALID_HOSTNAME => $message,
            Validator\EmailAddress::INVALID_MX_RECORD => $message,
            Validator\EmailAddress::INVALID_SEGMENT => $message,
            Validator\HostName::UNDECIPHERABLE_TLD => $message,
            Validator\HostName::LOCAL_NAME_NOT_ALLOWED => $message,
        ));

        $this->add(array(
            'name' => 'email',
            'required' => true,
            'break_on_failure' => true,
            'validators' => array(
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[[:alpha:][:alnum:]_]+([\.-]?[[:alpha:][:alnum:]_]+)*@[[:alpha:][:alnum:]_]+([\.-]?[[:alpha:][:alnum:]_]+)*(\.[[:alpha:][:alnum:]_]{2,3})+$/',
                        'messages' => array(
                            \Zend\Validator\Regex::INVALID => 'Somente os caracteres a-z, 0-9 - _ . são permitidos',
                            'regexNotMatch' => 'Favor informar um e-mail válido.',
                        ),
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Não pode estar em branco.',
                        ),
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => '\DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'object_repository' => $em->getRepository('Admin\Entity\Usuario'),
                        'fields' => 'email',
                        'message' => 'E-mail já associado a um usuário cadastrado. Caso tenha esquecido a senha, vá na opção "Problemas ao entrar" na página de entrada.',
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => 100,
                        'messages' => array(
                            'stringLengthTooLong' => 'E-mail muito grande.',
                        ),
                    ),
                ),
                $validator,
            ),
        ));

        $this->add(array(
            'name' => 'senha',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Não pode estar em branco.'),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'max' => 250,
                        'messages' => array(
                            'stringLengthTooShort' => 'Tem que ser no mínimo %min% caracteres.',
                            'stringLengthTooLong' => 'Senha maior do que o permitido.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Regex',
                    'options' => array(
                        //iniciand regexp com #
                        'pattern' => '#^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\.\,\;\:\ \_\(\)\\\/\'\"\!\@\#\$\%\&\*\+\=-]{6,}$#',
                        'messages' => array(
                            \Zend\Validator\Regex::INVALID => 'Deve ter ao menos uma letra e um número.',
                            'regexNotMatch' => 'Deve ter ao menos uma letra e um número. Caracteres permitidos (a-z, 0-9, !, @, #, $, %, &, *, -, +, =)',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'novasenha',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array('isEmpty' => 'Não pode estar em branco.'),
                    ),
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                        'max' => 250,
                        'messages' => array(
                            'stringLengthTooShort' => 'Tem que ser no mínimo %min% caracteres.',
                            'stringLengthTooLong' => 'Senha maior do que o permitido.',
                        ),
                    ),
                ),
                array(
                    'name' => 'Regex',
                    'options' => array(
                        //iniciand regexp com #
                        'pattern' => '#^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\.\,\;\:\ \_\(\)\\\/\'\"\!\@\#\$\%\&\*\+\=-]{6,}$#',
                        'messages' => array(
                            \Zend\Validator\Regex::INVALID => 'Deve ter ao menos uma letra e um número.',
                            'regexNotMatch' => 'Deve ter ao menos uma letra e um número. Caracteres permitidos (a-z, 0-9, !, @, #, $, %, &, *, -, +, =)',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'confirmacao',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Não pode estar em branco',
                        ),
                    ),
                ),
                array(
                    'name' => 'Identical', 'options' => array(
                        'token' => 'novasenha',
                        'messages' => array(
                            'notSame' => 'As senhas não conferem.',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'aceite',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            Validator\Digits::NOT_DIGITS => 'Você precisa aceitar e confirmar a leitura dos termos.',
                        ),
                    ),
                ),
            ),
        ));
    }

}
