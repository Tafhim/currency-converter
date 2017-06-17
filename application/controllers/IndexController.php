<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $rates = new Application_Model_RateMapper();
        $this->view->rates = $rates->fetchAll();
    }


}

