'use strict'

$(document).ready(function () {
    $(document).on('click', '.add-membership-prices', function (event) {
        event.preventDefault();
        $('#membership-prices').toggleClass('hidden');
    });
});