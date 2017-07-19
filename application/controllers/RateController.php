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
     *
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


}



