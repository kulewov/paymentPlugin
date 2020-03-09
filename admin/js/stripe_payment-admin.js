(function ($) {
    'use strict';
    $(() => {
        $('#PayPal_checkBox').click(() => {
            if ($('#PayPal_checkBox').prop('checked')) {
                $('#PayPal_checkBox').val('on');
                $('#paypal_id').closest("tr").css('display', 'table-row');
                $('#paypal_code').closest("tr").css('display', 'table-row');
                $('#PayPal_email_on').closest("tr").css('display', 'table-row');
                $('#PayPal_sandbox_on').closest("tr").css('display', 'table-row');
                $('#paypal_email').closest("tr").css('display', 'none');
            }
            else {
                $('#PayPal_checkBox').val('off');
                $('#paypal_id').closest("tr").css('display', 'none');
                $('#paypal_code').closest("tr").css('display', 'none');
                $('#PayPal_email_on').closest("tr").css('display', 'none');
                $('#PayPal_sandbox_on').closest("tr").css('display', 'none');
                $('#paypal_email').closest("tr").css('display', 'none');
            }
        });
        if ($('#PayPal_checkBox').val() == 'on') {
            $('#PayPal_checkBox').prop('checked', true);
            $('#paypal_id').closest("tr").css('display', 'table-row');
            $('#paypal_code').closest("tr").css('display', 'table-row');
            $('#PayPal_email_on').closest("tr").css('display', 'table-row');
            $('#PayPal_sandbox_on').closest("tr").css('display', 'table-row');
            $('#paypal_email').closest("tr").css('display', 'none');

        }
        else {
            $('#paypal_id').closest("tr").css('display', 'none');
            $('#paypal_code').closest("tr").css('display', 'none');
            $('#paypal_email').closest("tr").css('display', 'none');
            $('#PayPal_email_on').closest("tr").css('display', 'none');
            $('#PayPal_sandbox_on').closest("tr").css('display', 'none');

        }
        if ($('#PayPal_checkBox').prop('checked')) {
            if ($('#PayPal_email_on').val() == 'on') {
                $('#PayPal_email_on').prop('checked', true);
                $('#paypal_id').closest("tr").css('display', 'none');
                $('#paypal_code').closest("tr").css('display', 'none');
                $('#paypal_email').closest("tr").css('display', 'table-row');
            }

            else {
                $('#paypal_id').closest("tr").css('display', 'table-row');
                $('#paypal_code').closest("tr").css('display', 'table-row');
                $('#paypal_email').closest("tr").css('display', 'none');

            }
        }

        $('#Stripe_checkBox').click(() => {
            if ($('#Stripe_checkBox').prop('checked')) {
                $('#Stripe_checkBox').val('on');
                $('#stripe_p_key').closest("tr").css('display', 'table-row');
                $('#stripe_s_key').closest("tr").css('display', 'table-row');
            }
            else {
                $('#Stripe_checkBox').val('off');
                $('#stripe_p_key').closest("tr").css('display', 'none');
                $('#stripe_s_key').closest("tr").css('display', 'none');
            }
        });
        if ($('#Stripe_checkBox').val() == 'on') {
            $('#Stripe_checkBox').prop('checked', true);
            $('#stripe_p_key').closest("tr").css('display', 'table-row');
            $('#stripe_s_key').closest("tr").css('display', 'table-row');
        }
        else {
            $('#stripe_p_key').closest("tr").css('display', 'none');
            $('#stripe_s_key').closest("tr").css('display', 'none');
        }
        $('#PayPal_email_on').click(() => {
            if ($('#PayPal_email_on').prop('checked')) {
                $('#PayPal_email_on').val('on');
                $('#paypal_id').closest("tr").css('display', 'none');
                $('#paypal_code').closest("tr").css('display', 'none');
                $('#paypal_email').closest("tr").css('display', 'table-row');
            }
            else {
                $('#PayPal_email_on').val('off');
                $('#paypal_id').closest("tr").css('display', 'table-row');
                $('#paypal_code').closest("tr").css('display', 'table-row');
                $('#paypal_email').closest("tr").css('display', 'none');
            }
        })
        $('#PayPal_sandbox_on').click(() => {
            $('#PayPal_sandbox_on').prop('checked') ? $('#PayPal_sandbox_on').val('sandbox.paypal') : $('#PayPal_sandbox_on').val('paypal')
        })
        if ($('#PayPal_sandbox_on').val() == 'sandbox.paypal') {
            $('#PayPal_sandbox_on').prop('checked', true)
        }

    })
    $(() => {
        $('body div').children().first().prepend('<div id="modal_container"/>')
        $('#modal_container').append('<div id="modal_save"> Chages Saved </div>')
        $('#modal_container').css({
            'position': 'fixed',
            'overflow': 'auto',
            'left': '0',
            'top': '0',
            'width': '100%',
            'height': '100%',
            'background-color': 'rgba(0,0,0,0.5)',
            'z-index': '1001',
            'display': 'none'
        });
        $('#modal_save').css({
            'background': 'linear-gradient(to top right, #0841de, #09ffdd)',
            'padding': '30px',
            'z-index': '1024',
            'border-radius': '20px',
            'box-shadow': '-20px 20px 18px 2px #080707',
            'text-align': 'center',
            'min-width': '300px',
            'max-width': '450px',
            'position': 'fixed',
            'left': '50%',
            'top': '50%',
            'transform': 'translate(-50%, -50%)',
            'display': 'none',
            'color': 'white'
        });
    })
    $(() => {
        $('#save_payment').click(() => {
            $('#modal_container').show();
            $('#modal_save').show();
        })
    })

})(jQuery);
//$('#PayPal_sandbox_on').prop('checked')?val()='sandbox':val()='paypal'
