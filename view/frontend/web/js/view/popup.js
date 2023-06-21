define([
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/cookies',
    'jquery/ui'
], function ($, _, customerData, modal) {
    'use strict';

    return function (config) {
        var cookieName = 'oh_popup',
            newsletterInput = '.oh-popup #newsletter',
            customerInfo = customerData.get('customer'),
            popupElement = '#oh_popup',
            modalWindowPop = null;

        function createNewsPopup() {
            let data = {};

            if (customerInfo().email) {
                data = {email: customerInfo().email};
            }

            $.ajax({
                url: config.cookie_validate_url,
                method: 'GET',
                data: data,
                showLoader: false,
                success: function (response) {
                    if (response.need_render.toString() === 'true') {
                        setTimeout(function () {
                            createAndOpen(popupElement)
                        }, config.delay_time);
                    }
                }
            });

            bindNewsl();
        }

        function bindNewsl() {
            $('.oh-popup .newsletter .primary').click(function (e) {
                if (!_.isEmpty($(newsletterInput).val()) &&
                    isEmail($(newsletterInput).val())) {

                    //send google event
                    if (typeof ga !== "undefined") {
                        ga('send', 'event', 'customer', 'newsletter create', 'subscribe newsletter');
                    }
                }
            });
        }

        /** Create popup window */
        function createAndOpen(element) {
            modalWindowPop = $(element);
            var options = {
                'type': 'popup',
                'modalClass': 'oh-popup ' + config.block_id,
                'autoOpen': false,
                'responsive': true,
                'buttons': []
            };
            modal(options, $(modalWindowPop));
            showModal(element);
        }

        function showModal(element) {
            $(element).modal('openModal');

            //set cookie to avoid reopen
            setCookie();
        }

        function closeModal() {
            $(modalWindowPop).modal('closeModal');
        }

        /**
         * Set cookie to avoid show several times
         */
        function setCookie() {
            if (_.isEmpty($.mage.cookies.get(cookieName))) {
                let cookieExpires = new Date();
                cookieExpires.setDate(cookieExpires.getDate() + 10); //45 days off
                $.mage.cookies.set(cookieName, JSON.stringify({need_render: false}), {expires: cookieExpires});
            }
        }

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

        createNewsPopup();
    }
});
