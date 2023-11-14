require([
    "jquery"
], function($){
    'use strict';

    $('input.cs-csfeature__logo').each(function() {
        $(this).parent().siblings('label').addClass('cs-csfeature__logo');
    });

    $('select.cs-csfeature__logo').each(function() {
        $(this).parent().siblings('.label').find('label').addClass('cs-csfeature__logo');
    });
});
