<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // Build a history list
        $this->view->latestHistory = $this->_helper->historyList();
        
        // For rendering in the view
        $this->view->converterForm = $this->_helper->converter();
    }


}

