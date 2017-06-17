<?php

class RateController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('convert', 'json')
                    ->initContext();
    }

    public function indexAction()
    {
        // action body
    }

    public function convertAction()
    {
        $this->view->data = array('data', 'data1', 'data2');
    }


}



