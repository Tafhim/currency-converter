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
            $this->bootstrap( 'FrontController' );
            $front = $this->getResource( 'FrontController' );
            $front->setParam('disableOutputBuffering', true);
            require_once 'router/cli.php';
            $front->setRouter( new Application_Router_Cli() );
            $front->setRequest( new Zend_Controller_Request_Simple() );
        }
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
        $error->setErrorHandlerController('index');

        if (PHP_SAPI == 'cli')
        {
            $error->setErrorHandlerController ('error');
            $error->setErrorHandlerAction ('cli');
        }
    }

}

