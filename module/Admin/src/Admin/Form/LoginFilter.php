<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Admin\Form;

use Zend\InputFilter\InputFilter,
    Zend\Validator,
    Base\Form\Validator\Cpf;

class LoginFilter  extends InputFilter
{
    
    public function __construct() 
    {
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
                    'name' => Cpf::class,
                    'options' => array(
                        'acceptFakeNumbers' => true
                    )
                ),
            ),
        ));
        
        $this->add(array(
           'name'=>'email',
            'required'=>true,
            'filters' => array(
                array('name'=>'StripTags'),
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'=>'NotEmpty',
                    'options'=>array(
                        'messages'=>array(
                            'isEmpty'=>'Não pode estar em branco.'
                        )
                    )
                ),
                array(
                    'name'=>'StringLength',
                    'options'=>array(
                        'min'=>5,
                        'max'=>100,
                        'messages' => array( 
                            Validator\StringLength::TOO_LONG => 'Nome não pode ter mais de %max% caracteres.',
                            Validator\StringLength::TOO_SHORT => 'Tem que ser no mínimo %min% caracteres.'
                        ) 
                    )
                )
            )
        ));
        
        $this->add(array(
            'name'=>'password',
            'required'=>true,
            'filters' => array(
                array('name'=>'StripTags'),
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'=>'NotEmpty',
                    'options'=>array(
                        'messages'=>array('isEmpty'=>'Não pode estar em branco.')
                    )
                ),
                array(
                    'name'=>'StringLength',
                    'options'=>array(
                        'min'=>6,
                        'max'=>250,
                        'messages' => array( 
                            'stringLengthTooShort' => 'Tem que ser no mínimo %min% caracteres.',
                            'stringLengthTooLong' => 'Senha maior do que o permitido.'
                        ) 
                    ),
                ),
            )
        ));
    }
    
}
