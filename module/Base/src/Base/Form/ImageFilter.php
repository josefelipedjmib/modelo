<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Base\Form;

use Zend\InputFilter\InputFilter,
    Zend\Validator;

class ImageFilter extends InputFilter
{
    
    public function __construct() 
    {
        
        $this->add(array(
            'name'=>'imagem',
            'type' => '\Zend\InputFilter\FileInput',
            'required'=>true,
            'validators' => array(
                [
                    'name' => 'fileuploadfile',
                    'options'=>[
                        'message' => 'Escolha um arquivo'
                    ],
                    'break_chain_on_failure' => true
                ],
                array(
                    'name'=>'filesize',
                    'options'=>array(
                        'max'=>600*1024,
                        'message' => 'O arquivo não pode ser maior que %max%.'
                    )
                ),
                array(
                    'name'=>'fileextension',
                    'options'=>array(
                        'extension' => ['jpg', 'png'],
                        'message' => 'Só pode ser arquivo no formato JPG ou PNG.'
                    )
                )
            ),
            'filters'=> array(
                array(
                    'name'=>'filerenameupload',
                    'options'=>array(
                        'target'=>'./data/perfil.jpg',
                        'randomize' => true
                    )
                )
            )
        ));
    }
    
}
