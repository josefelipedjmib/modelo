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

class Configger extends AbstractHelper {

    public function __construct($config)
    {
        $this->configger = $config;
    }

    public function __invoke()
    {
        return $this->configger;
    }

    protected $configger;
}
