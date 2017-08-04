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

    /**
     * Convert currency
     */
    public function convertAction()
    {
        // Generate the request fetcher object
        $request = new Zend_Controller_Request_Http();
        
        // Get the request parameters
        $convert_from = $request->getPost('convert_from');
        $from_currency = $request->getPost('from_currency');
        $to_currency = $request->getPost('to_currency');

        // Fetch currency rates
        $rates = new Application_Model_RateMapper();
        $from_rate = $rates->findByCode($from_currency)->rate;
        $to_rate = $rates->findByCode($to_currency)->rate;

        // Calculate the result, first convert to USD, then convert to desired currency
        $result = ( (float)$convert_from / (float)$from_rate );
        $result = round( $result * (float)$to_rate, 6 );

        // Add this conversion to the history
        $history_entry = new Application_Model_History();
        $history_entry->setFromAmount($convert_from)
                    ->setToAmount($result)
                    ->setFrom($from_currency)
                    ->setTo($to_currency);
        $history = new Application_Model_HistoryMapper();
        $history->save($history_entry);

        $this->view->result = $result;
    }

    /**
     * Fetching rates from the API and updating the cache
     */
    public function fetchAction()
    {
        // Disabling all html
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        // Caching instance
        $cache = Zend_Cache::factory('File', 'File', 
        array(
            'ignore_missing_master_files' => true,
            'debug_header' => true,
            'lifetime' => 3600,
            'master_files' => array('testMasterFile'),
            'automatic_serialization' => true,
        ),
        array(
            'cache_dir' => '/var/www/html/public/rateCache'
        ));


        $rate_cache_id = 'RatesFromAPICache';

        // Check if the cache is fresh enough, if so use it
        if ( ($cacheRates = $cache->load($rate_cache_id)) === false ) {
            // No cache available
            $cacheRates = null;

            // Client for the request
            $api_request_client = new Zend_Http_Client('http://api.fixer.io/latest');
            $api_request_client->setParameterGet('base', 'USD');
            
            // Send the request
            $currency_api_response = $api_request_client->request('GET');

            // Decode the response
            $response = json_decode($currency_api_response->getBody());

            $rates = array();
            foreach( $response->rates as $currency => $rate ) {
                $rates[(string)$currency] = (float)$rate;
            }
            $rates[$response->base] = (float)1.000;

            // Set the response for caching
            $cacheRates = $rates;

            $cache->save( $cacheRates );

        }

        return $cacheRates;

    }


}





