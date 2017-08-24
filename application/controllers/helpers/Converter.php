<?php


class Application_Controller_Helper_Converter extends Zend_Controller_Action_Helper_Abstract
{
    public function direct()
    {
        // Caching instance
        $formCache = Zend_Registry::get('FormCache');

        // Set the Rate cache ID
        $formCacheId = 'FormCache';

        // Load the rate options
        $rateOptions = $this->getRates();

        // Check if the cache is fresh enough, if so use it
        if ( ($cacheOutput = $formCache->load($formCacheId)) === false ) {
            // Build the form
            $converterForm = new Application_Form_RateConverter();
            
            // Set the rate options;
            $converterForm->from_currency->setMultiOptions($rateOptions);
            $converterForm->to_currency->setMultiOptions($rateOptions);

            // Capture the cacheable content
            $cacheOutput = $converterForm;

            // Cache the content
            $formCache->save($cacheOutput);
        }

        return $cacheOutput;
    }

    private function getRates()
    {
        // Fetch all rates from the DB
        $rates = new Application_Model_RateMapper;
        
        // Build a rate option array
        $ratesData = $rates->fetchAll('rates_options');

        return $ratesData;
    }
}