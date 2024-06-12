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
    Zend\Validator;

class EnderecoFilter extends InputFilter
{
    
    public function __construct() 
    {
        $this->add(array(
           'name'=>'cep',
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
                        'min'=>8,
                        'max'=>10,
                        'messages' => array( 
                            Validator\StringLength::TOO_LONG => 'Nome não pode ter mais de %max% caracteres.',
                            Validator\StringLength::TOO_SHORT => 'Tem que ser no mínimo %min% caracteres.'
                        ) 
                    )
                )
            )
        ));
        
        $this->add(array(
           'name'=>'pais',
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
                )
            )
        ));
        
        $this->add(array(
           'name'=>'estado',
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
                )
            )
        ));
        
        $this->add(array(
           'name'=>'cidade',
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
                )
            )
        ));
        
        $this->add(array(
           'name'=>'endereco',
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
                )
            )
        ));
        
        $this->add(array(
           'name'=>'numero',
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
                )
            )
        ));
    } 
    
}
