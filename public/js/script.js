(function($) {
    'use strict'
    
    // Target form identifier
    var form = 'form#currency-converter';

    // Extending the jquery module to add our validation libraries
    $.fn.extend({
        checkForValidCurrencyCode: function() {
            $currency_codes = ["AED","AFN","ALL","AMD","ANG","AOA","ARS","AUD","AWG","AZN","BAM","BBD","BDT","BGN","BHD","BIF","BMD","BND","BOB","BOV","BRL","BSD","BTN","BWP","BYR","BZD","CAD","CDF","CHE","CHF","CHW","CLF","CLP","CNY","COP","COU","CRC","CUC","CUP","CVE","CZK","DJF","DKK","DOP","DZD","EGP","ERN","ETB","EUR","FJD","FKP","GBP","GEL","GHS","GIP","GMD","GNF","GTQ","GYD","HKD","HNL","HRK","HTG","HUF","IDR","ILS","INR","IQD","IRR","ISK","JMD","JOD","JPY","KES","KGS","KHR","KMF","KPW","KRW","KWD","KYD","KZT","LAK","LBP","LKR","LRD","LSL","LYD","MAD","MDL","MGA","MKD","MMK","MNT","MOP","MRO","MUR","MVR","MWK","MXN","MXV","MYR","MZN","NAD","NGN","NIO","NOK","NPR","NZD","OMR","PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR","RON","RSD","RUB","RWF","SAR","SBD","SCR","SDG","SEK","SGD","SHP","SLL","SOS","SRD","SSP","STD","SVC","SYP","SZL","THB","TJS","TMT","TND","TOP","TRY","TTD","TWD","TZS","UAH","UGX","USD","USN","UYI","UYU","UZS","VEF","VND","VUV","WST","XAF","XCD","XDR","XOF","XPF","XSU","XUA","YER","ZAR","ZMW","ZWL"];
            if ( $currency_codes.indexOf( this.val() ) != -1 )
                return true;

            // since the form is not valid we are giving it a class to inidicate the error
            $(this).addClass('invalid-input');

            return false;
        },
        checkForValidNumbers: function() {
            // first check if the field is a disabled field
            if ( $(this).is(':disabled') ) return true;

            $number = this.val();
            $is_valid = (!$.isArray( $number ) && ($number - parseFloat( $number ) + 1) >= 0);

            if ( !$is_valid )
                $(this).addClass('invalid-input');

            return $is_valid;
        },
        validateConverterInput: function() {
            // initialization of final result
            finalResult = true;

            // find the elements we want to validate
            $selectElements = this.find('select');
            $textElements = this.find('input[type="text"]');

            // validate select elements (currecy codes)
            $selectElements.each(function(){
                finalResult &= $(this).checkForValidCurrencyCode();
            });

            // validate input elements (values)
            $textElements.each(function() {
                finalResult &= $(this).checkForValidNumbers();
            });

            return finalResult;
        }
    });

    // Event bindings for the form
    $(document).ready(function($) {
        // remove the invalid markers
        $(document).on('click', form, function(evt) {
            $(form + ' *').removeClass('invalid-input');
        });

        // Call the api for currenty rates
        $(document).on('submit', form, function(evt) {
            // Stop the default request behavior
            evt.preventDefault();

            formIsValid = $(form).validateConverterInput();

            // this should be removed
            if ( !formIsValid )
                return false;

            // Get all the form data for ajax 
            formData = new FormData();
            formData.append('convert_from', $(form).find('[name="convert_from"]').val());
            formData.append('from_currency', $(form).find('[name="from_currency"]').val());
            formData.append('to_currency', $(form).find('[name="to_currency"]').val());
            formData.append('csrf_token', $(form).find('[name="csrf_token"]').val());

            // Table for history
            table_last_row = 'table#history-table tr:last';
            
            // Push an ajax post request with all the data
            $.post({
                url: 'rate/convert/format/json',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Validate if the response has no error
                    if ( response.hasOwnProperty('error') ) {
                        alert(response.message);
                        console.log( response.request );
                        return false;
                    }

                    // Validate if response is correct
                    if ( ! response.hasOwnProperty('result') ) {
                        alert('Conversion failed');
                        return false;
                    }
                        
                    
                    // Check if the result is numeric
                    if ( ! $.isNumeric(response.result) ) {
                        alert('Convesion failed');
                        return false;
                    }

                    // Update the result field
                    $(form).find('[name="convert_to"]').val(response.result);
                    
                    // Remove the first entry of history if the number of rows is 5
                    $('table#history-table tr:nth-child(5)').siblings('tr:first-child').remove();

                    // Add new history row
                    $(table_last_row).after('<tr><td>'+formData.get('from_currency')+'</td><td>'+formData.get('convert_from')+'</td><td>'+response.result+'</td><td>'+formData.get('to_currency')+'</td></tr>');
                }
            }).fail(function(response, status, error) {
                alert('Server error!');
            });

            // Make sure the form submisison does not happen
            return false;
        });

        // Swap the currencies
        $(document).on('click', '#converter-swap', function(evt) {
            // Get the selections
            $from_currency = $(form).find('[name="from_currency"]').val();
            $to_currency = $(form).find('[name="to_currency"]').val();

            // Swap the selections
            $(form).find('[name="from_currency"]').val($to_currency);
            $(form).find('[name="to_currency"]').val($from_currency);
        });
    });
})(jQuery);