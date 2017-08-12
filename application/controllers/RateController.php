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
        $request = $this->getRequest();
        
        // Validate the form for CSRF
        $form = new Application_Form_RateConverter();
        if ( ! $form->verifyCSRF( $request ) ) {
            $this->view->request = $request->getPost();
            $this->view->error = 'Invalid conversion request';
            $this->view->message = 'CSRF authentication failed';
            return;
        }
        
        // Get the request parameters
        $convert_from = $request->getPost('convert_from');
        $from_currency = $request->getPost('from_currency');
        $to_currency = $request->getPost('to_currency');

        // Fetch currency rates
        $rateMapper = new Application_Model_RateMapper;
        $rates = $rateMapper->fetchAll('rates_data');
        
        $from_rate = $rates[$from_currency];
        $to_rate = $rates[$to_currency];

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

        $rates = new Application_Model_RateMapper;
        $ratesData = $rates->fetchAll();

        if (PHP_SAPI == 'cli')
            echo '<pre>' . print_r( $ratesData, true ) . '</pre>';
        
        return $ratesData;
    }


}





