// always submit disabled fields
$('form').submit(function(e2) {
    $(':disabled').each(function(e3) {
        $(this).removeAttr('disabled');
    })
});