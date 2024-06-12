<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base\Form;

use Zend\Form\Form;

class AbstractForm  extends Form
{
    public function __construct($name = null, $options = array()) {
        parent::__construct($name, $options);
    }

    public function manterApenasCampos($array){
        $campos = $this->getElements();
        foreach($campos as $k => $v){
            if(!in_array($k, $array)){
                $this->remove($k);
                $this->getInputFilter()->remove($k);
            }
        }
    }

    public function removerCampos($array){
        foreach ($array as $campo)
        {
            $this->remove($campo);
            $this->getInputFilter()->remove($campo);
        }
    }

    public function removerValidador($element, $instanceValidator){
        $newValidatorChain = new \Zend\Validator\ValidatorChain;
        foreach ($element->getValidatorChain()->getValidators()
        as $validator) {

            if (! ($validator['instance'] instanceof $instanceValidator)) {
                $newValidatorChain->addValidator($validator['instance'],
                                     $validator['breakChainOnFailure']);
            }
        }
        $element->setValidatorChain($newValidatorChain);
    }
    
}