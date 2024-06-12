<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Base\View\Helper;

use Zend\View\Helper\AbstractHelper;
use \Base\Mib\URL;

class Logger extends AbstractHelper {

    public function __invoke($logName = "") {
        $this->logger = new \Zend\Log\Logger;
        $this->writer($logName);
        return $this->logger;
    }

    private function writer($logName){
        if(empty($logName))
            $logName = "error";
        $writer = new \Zend\Log\Writer\Stream(URL::getInstance()->getRoot().'../data/log/'.date('Y-m-d').'-'.$logName.'.log');
        $this->logger->addWriter($writer);
    }

    protected $logger;

}
