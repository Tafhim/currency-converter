<?php

class Application_Model_RateMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Rate');
        }
        return $this->_dbTable;
    }
    
    public function save(Application_Model_Rate $rate)
    {
        $data = array(
            'code'   => $rate->getCode(),
            'rate' => $rate->getRate(),
            'created' => date('Y-m-d H:i:s'),
        );
 
        if (null === ($id = $rate->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Rate $rate)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $rate->setId($row->id)
             ->setCode($row->code)
             ->setRate($row->rate)
             ->setCreated($row->created);
    }

    public function findByCode($code) {
        $result = $this->getDbTable()->fetchRow(
            'code = "' . $code . '"'
        );

        return $result;
    }

    public function fetchAll($key = null)
    {
        // Caching instance
        $cache = Zend_Registry::get('RateCache');


        $rate_cache_id = 'RatesFromAPICache';

        // Check if the cache is fresh enough, if so use it
        if ( ($cache_data = $cache->load($rate_cache_id)) === false ) {
            // Client for the request
            $api_request_client = new Zend_Http_Client('http://api.fixer.io/latest');
            $api_request_client->setParameterGet('base', 'USD');
            
            // Send the request
            $currency_api_response = $api_request_client->request('GET');

            // Decode the response
            $response = json_decode($currency_api_response->getBody());

            $cache_rates = array();
            $cache_rate_options = array();
            foreach( $response->rates as $currency => $rate ) {
                $cache_rates[$currency] = $rate;
                $cache_rate_options[$currency] = $currency;
            }
            $cache_rates[$response->base] = 1.000;
            $cache_rate_options[$response->base] = $response->base;


            $cache_data['rates_data'] = $cache_rates;
            $cache_data['rates_options'] = $cache_rate_options;

            $cache->save( $cache_data );

        }

        // Check if a particular data type was selected
        if ( !empty($key) && isset( $cache_data[$key] ) ) {
            return $cache_data[$key];
        } 

        // Return it all
        return $cache_data;
    }

}

