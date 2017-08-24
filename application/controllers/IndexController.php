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

        // Fetch all rates from the DB
        $rates = new Application_Model_RateMapper;
        
        // Build a rate option array
        $rate_options = $rates->fetchAll('rates_options');

        // Build the form
        $converterForm = new Application_Form_RateConverter();
        
        // Set the rate options;
        $converterForm->from_currency->setMultiOptions($rate_options);
        $converterForm->to_currency->setMultiOptions($rate_options);
        
        // For rendering in the view
        $this->view->converterForm = $converterForm;
    }


}

