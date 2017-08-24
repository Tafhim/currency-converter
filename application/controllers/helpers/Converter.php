<?php


class Application_Controller_Helper_Converter extends Zend_Controller_Action_Helper_Abstract
{
    public function direct()
    {
        // Fetch all rates from the DB
        $rates = new Application_Model_RateMapper;
        
        // Build a rate option array
        $rateOptions = $rates->fetchAll('rates_options');

        // Build the form
        $converterForm = new Application_Form_RateConverter();
        
        // Set the rate options;
        $converterForm->from_currency->setMultiOptions($rateOptions);
        $converterForm->to_currency->setMultiOptions($rateOptions);

        return $converterForm;
    }
}