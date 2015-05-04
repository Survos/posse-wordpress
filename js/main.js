/**
 * Main scripts for posse plugin
 * @author Piotr Grochowski <piogrek@gmail.com>
 */
jQuery(function ($) {
    console.log('posse plugin init (nothing loaded)');

    var resizeIframe = function (obj) {
        var height = obj.contentWindow.document.body.scrollHeight + 'px';
        obj.style.height = height;
        obj.parentNode.style.height = height;
    }

    var handleIframeReload = function (iframe) {
        var $iframe = $(iframe);
        $iframe.load(function (e) {
            if (!$iframe.data('loaded')) {
                $iframe.data('loaded', 1);
                //console.log('iframe loaded');
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
    $('.embed-autosize iframe').each(function (a, b) {
        var iframe = $(b);
        iframe.on('load', function () {
            resizeIframe(b);
        });
    });

    // $('.user-calendar').fullCalendar({});
});