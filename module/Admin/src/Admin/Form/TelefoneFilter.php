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

class TelefoneFilter extends InputFilter
{
    
    public function __construct() 
    {
        
        $this->add(array(
           'name'=>'telefone',
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
                        'min'=>9,
                        'max'=>30,
                        'messages' => array( 
                            Validator\StringLength::TOO_LONG => 'Nome não pode ter mais de %max% caracteres.',
                            Validator\StringLength::TOO_SHORT => 'Tem que ser no mínimo %min% caracteres.'
                        ) 
                    )
                )
            )
        ));
    }
    
}
