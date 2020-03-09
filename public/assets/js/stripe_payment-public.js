(function ($) {
    'use strict';

    /**---------Stripe functions-----------*/
    function stripe_submit() {
        $('#expMonth').keyup(() => {
            if ($('#expMonth').val().length == 2)
                $('#expYear').focus();
        });
        let $form = $('#stripe_payment_form');
        $form.submit((e) => {
            e.preventDefault();
            $form.find('.submit').prop('disabled', true);
            Stripe.card.createToken($form, stripeResponseHandler);
            return false;
        });
    }

    function stripeResponseHandler(status, response) {
        let $form = $('#stripe_payment_form');
        if (response.error) {
            $form.find('.payment-errors').text(response.error.message);
            $form.find('.submit').prop('disabled', false);
        } else {
            let token = response.id;
            /**Ajax query for add new payer*/
            $form.append($('<input type="hidden" name="stripeToken" id="Token">').val(token));
            let tok = $('#Token').val();
            let sum = $('#sumPayment').val();
            let cardNumber = $("#cardNumber").val();
            $.ajax({
                url: myajax.url,
                method: 'post',
                data: {
                    action: 'payment_order',
                    sum: sum,
                    cardNumber: cardNumber,
                    token: tok,
                    system: 'stripe'
                },
                success: (e) => {
                    window.location.replace(e);
                },
                error: (e) => {
                    alert(e);
                }
            });
        }
    };

    /**------PayPal functions--------------*/
    function pay_pal_submit() {
        $('#PaypalPhone').mask("+999(99) 999-99-99");
        $('#PaypalSend').click((e) => {
            e.preventDefault();
            let form = $('#paypal_payment_form');
            form.validate({
                rules: {
                    email: {
                        required: true,
                        MyEmail: true
                    },
                    sum: {
                        required: true
                    },
                    phone: {
                        required: true
                    }
                }
            });
            if ($('#paypal_payment_form').valid()) {
                let tel = $("#PaypalPhone").val();
                let email = $("#PaypalEmail").val();
                let sum = $("#PaypalSum").val();
                let val = $("#PaypalCurrency").val();
                $.ajax({
                    url: myajax.url,
                    method: 'post',
                    data: {
                        action: 'payment_order',
                        phone: tel,
                        email: email,
                        sum: sum,
                        val: val,
                        system: 'paypal'
                    },
                    success: (r) => {
                        window.location.replace(r);
                    },
                    error: (r) => {
                        alert('error');
                    }
                });
            }
        });
    }

    /**-------------Buy button-------------*/
    $(() => {
        $('#buy_button').click(() => {
            $.ajax({
                dataType: 'json',
                url: myajax.url,
                method: 'post',
                data: {
                    action: 'page_loader'
                },
                success: (e) => {
                    if (e.stripe == 'on') {
                        $('#paypal_svg').css('display', 'none');
                        $('#paypal_radio').hide();
                        $('#form_container').append(e.stripe_form);
                        stripe_submit();
                    }
                    if (e.paypal == 'on') {
                        $('#stripe_svg').css('display', 'none');
                        $('#stripe_radio').hide();
                        $('#paypal_radio').prop("checked", true);
                        $('#form_container').append(e.paypal_form);
                        pay_pal_submit();
                    }
                }
            });
            $('#modal_container').show();
            $('#form_container').show();
            if ($('#stripe_radio').css('display') == "none") {
                $('#paypal_radio').prop("checked", true);
                $('#paypal_payment_form').show();
                $('#stripe_payment_form').hide();
            } else {
                $('#stripe_radio').prop("checked", true);
                $('#stripe_payment_form').show();
                $('#paypal_payment_form').hide();
            }
            $('body').toggleClass('modal_on');
        })
    })

    $(() => {
        $('#paypal_radio').click(() => {
            $('#stripe_payment_form').hide();
            $('#paypal_payment_form').show();
        });
        $('#stripe_radio').click(() => {
            $('#stripe_payment_form').show();
            $('#paypal_payment_form').hide();
        })
    })
    $(() => {
        $('#modal_container').click(() => {
            $('#modal_container').hide();
            $('#form_container').hide();
            $('body').toggleClass('modal_on');
            $('#cardNumber').val('');
            $('#expYear').val('');
            $('#expMonth').val('');
            $('#CVC').val('');
            $('#PaypalPhone').val('');
            $('#PaypalEmail').val('');
            $('#PaypalSum').val('');
            $('#stripe_radio').prop("checked", true);
            $("select option[value='default']").attr("selected", "selected");
        })
    })
    /**-----------Form validation----------*/
    $(() => {
        $.validator.addMethod("MyEmail", function (value, element) {
            return /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/.test(value);
        }, "Please enter a valid email address. As example@domain.com")
    });

    /*------Custom code-----*/
    /*----------------------*/
})(jQuery);
