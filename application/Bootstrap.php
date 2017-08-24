<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    protected function _initRouter()
    {
        if( PHP_SAPI == 'cli' )
        {
            require_once 'router/cli.php';

            $this->bootstrap( 'FrontController' );
            $front = $this->getResource( 'FrontController' );
            $front->setParam('disableOutputBuffering', true);
            
            $front->setRouter( new Library_Router_Cli() );
            $front->setRequest( new Zend_Controller_Request_Simple() );
        }
    }

    /*
     * Autoloader configuration
     */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Application',
            'basePath'  => APPLICATION_PATH,
        ));

        /*
         * Register history listing helper
         */ 
        Zend_Controller_Action_HelperBroker::addPath(
            APPLICATION_PATH . '/controllers/helpers', 
            'Application_Controller_Helper_');

        return $autoloader;
    }

    protected function _initRoutes ()
    {
        $router = Zend_Controller_Front::getInstance ()->getRouter ();
        if ($router instanceof Zend_Controller_Router_Rewrite)
        {
            // put your web-interface routes here, so they do not interfere
        }
    }

    protected function _initError ()
    {
        $this->bootstrap( 'FrontController' );
        $front = $this->getResource( 'FrontController' );
        $front->registerPlugin( new Zend_Controller_Plugin_ErrorHandler() );
        $error = $front->getPlugin ('Zend_Controller_Plugin_ErrorHandler');
        $error->setErrorHandlerController('error');

        if (PHP_SAPI == 'cli')
        {
            $error->setErrorHandlerController ('error');
            $error->setErrorHandlerAction ('cli');
        }
    }

    protected function _initCache()
    {
        $ratesCacheFrontEndOptions = array(
            'ignore_missing_master_files' => true,
            'debug_header' => true,
            'lifetime' => 3600,
            'master_files' => array('testMasterFile'),
            'automatic_serialization' => true,
        );
        $ratesCacheBackEndOptions = array(
            'cache_dir' => '/tmp/'
        );
        $ratesCache = Zend_Cache::factory('File', 'File', $ratesCacheFrontEndOptions, $ratesCacheBackEndOptions);
        Zend_Registry::set('RateCache', $ratesCache);
    }

}

