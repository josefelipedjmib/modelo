<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;


class ReaderController extends AbstractActionController
{

    public function __construct(){
        
    }
    
    public function indexAction(){
        
        $list = $this->getQuery();
        $page = $this->params()->fromRoute('page');
        
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)->setDefaultItemCountPerPage(10);
        
        return new ViewModel(array('data'=>$paginator, 'page'=>$page));
    }

    public function detalheAction(){

        $id = $this->params()->fromRoute('id');
        $list = $this->getDetalhes($id);

        return new ViewModel(array('data'=>$list));
    }
    
    public function getQuery(){
        return $this->getEm()->getRepository($this->entity)->findAll();
    }

    public function getDetalhes($id){
        return $this->getEm()->getRepository($this->entity)->findOneBy(array('id' => $id)); 
    }

    /**
    * @return ServiceLocatorInterface
    */
    public function getServiceLocator(){
        return $this->serviceLocator;
    }
    
    /**
    *
    * @return EntityManager
    */
    public function getEm(){
        if(null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return $this->em;
    }
    
    protected $em;
    protected $service;
    protected $entity;
    protected $form;
    protected $route;
    protected $controller;
    protected $serviceLocator;
}
