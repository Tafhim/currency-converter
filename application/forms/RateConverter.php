<?php

class Application_Form_RateConverter extends Zend_Form
{

    public function init()
    {
        // Form ID
        $this->setAttrib('id', 'currency-converter');

        // Request method
        $this->setMethod('post');

        // Fields
        $convertFromValue = new Zend_Form_Element_Text('convert_from');
        $fromCurrency = new Zend_Form_Element_Select('from_currency');
        $toCurrency = new Zend_Form_Element_Select('to_currency');
        $convertToValue = new Zend_Form_Element_Text('convert_to');
        $convertToValue->setAttrib('disabled', "disabled");
        $submitButton = new Zend_Form_Element_Submit('Convert');
        $swapButton = new Zend_Form_Element_Button('Swap');
        $swapButton->setAttrib('id', 'converter-swap');
        
        // Create the csrf element
        $csrfToken = new Zend_Form_Element_Hidden('csrf_token');
        $csrfToken->setValue($this->getCSRF())
                  ->setRequired(true)
                  ->removeDecorator('HtmlTag')
                  ->removeDecorator('label');

        // Add all the elements to the form
        $this->addElement($fromCurrency);
        $this->addElement($convertFromValue);
        $this->addElement($convertToValue);
        $this->addElement($toCurrency);
        $this->addElement($submitButton);
        $this->addElement($swapButton);
        $this->addElement($csrfToken);
    }

    public function getCSRF() {
        Zend_Session::start();

        if ( !Zend_Session::namespaceIsset('csrf_token') ) {
            $csrf_token_authspace = new Zend_Session_Namespace('csrf_token');
            $csrf_token_authspace->setExpirationSeconds(3600);
            $csrf_token_authspace->csrf_token = md5(uniqid());
        } else {
            $csrf_token_authspace = Zend_Session::namespaceGet('csrf_token');
        }

        return $csrf_token_authspace['csrf_token'];
    }

    public function verifyCSRF($formRequest) {
        $csrf_token = $this->getCSRF();

        if ($csrf_token == $formRequest->getPost('csrf_token')) {
            return true;
        }
        
        return false;
    }


}

