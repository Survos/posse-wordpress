/**
 * Main scripts for posse plugin
 * @author Piotr Grochowski <piogrek@gmail.com>
 */
jQuery(function ($) {
    console.log('posse plugin init (nothing loaded)');

    var handleIframeReload = function (iframe) {
        var $iframe = $(iframe);
        $iframe.load(function (e) {
            if (!$iframe.data('loaded')) {
                $iframe.data('loaded', 1);
                console.log('iframe loaded');
            } else {
                if ($iframe.data('reload-href')) {
                    window.location.assign($iframe.data('reload-href'));
                } else {
                    window.document.location.reload();
                }
            }
        });
    };

    $('[data-parent-reload]').each(function (a, b) {
        handleIframeReload(b);
    });

    // $('.user-calendar').fullCalendar({});
});