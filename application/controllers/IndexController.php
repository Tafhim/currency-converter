<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // Fetch all history from the DB
        $history = new Application_Model_HistoryMapper();

        // Build a history list
        $this->view->history = $history->fetchAll();

        // Fetch all rates from the DB
        $rates = new Application_Model_RateMapper();
        
        // Build a rate option array
        $rate_options = array();
        foreach ($rates->fetchAll() as $rate) {
            $rate_options[$rate->getCode()] = $rate->getCode();
        }

        // Build the form
        $converterForm = new Application_Form_RateConverter();
        
        // Set the rate options;
        $converterForm->from_currency->setMultiOptions($rate_options);
        $converterForm->to_currency->setMultiOptions($rate_options);
        
        // For rendering in the view
        $this->view->converterForm = $converterForm;
    }


}

