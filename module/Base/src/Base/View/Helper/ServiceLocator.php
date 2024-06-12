<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorInterface;

class ServiceLocator extends AbstractHelper {

    public function __construct(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getSl(){
        return $this->serviceLocator;
    }
    
    /**
    *
    * @return EntityManager
    */
    public function getEm(){
        if(null === $this->em)
            $this->em = $this->getSl()->get('Doctrine\ORM\EntityManager');
        return $this->em;
    }

    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;
    protected $em;
}
