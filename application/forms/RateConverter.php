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

        // Add all the elements to the form
        $this->addElement($fromCurrency);
        $this->addElement($convertFromValue);
        $this->addElement($convertToValue);
        $this->addElement($toCurrency);
        $this->addElement($submitButton);
        $this->addElement($swapButton);
    }


}

