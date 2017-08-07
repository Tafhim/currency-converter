var form = 'form#currency-converter';
(function($) {
    $(document).ready(function($) {
        // Call the api for currenty rates
        $(document).on('submit', form, function(evt) {
            // Stop the default request behavior
            evt.preventDefault();

            // Get all the form data for ajax 
            formData = new FormData();
            formData.append('convert_from', $(form).find('[name="convert_from"]').val());
            formData.append('from_currency', $(form).find('[name="from_currency"]').val());
            formData.append('to_currency', $(form).find('[name="to_currency"]').val());

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
                    // Update the result field
                    $(form).find('[name="convert_to"]').val(response.result);
                    
                    // Remove the first entry of history if the number of rows is 5
                    $('table#history-table tr:nth-child(5)').siblings('tr:first-child').remove();

                    // Add new history row
                    $(table_last_row).after('<tr><td>'+formData.get('from_currency')+'</td><td>'+formData.get('convert_from')+'</td><td>'+response.result+'</td><td>'+formData.get('to_currency')+'</td></tr>');
                }
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