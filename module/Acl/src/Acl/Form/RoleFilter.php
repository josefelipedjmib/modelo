<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Acl\Form;

use Zend\InputFilter\InputFilter;

class RoleFilter  extends InputFilter
{
    
    public function __construct() 
    {
        
        $this->add(array(
           'name'=>'parent',
            'required'=>false,
            'allowEmpty' => true,
            'filters' => array(
                array('name'=>'StripTags'),
                array('name'=>'StringTrim'),
            )
        ));
    }
    
}
