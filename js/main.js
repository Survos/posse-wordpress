/**
 * Main scripts for posse plugin
 * @author Piotr Grochowski <piogrek@gmail.com>
 */
jQuery(function ($) {
    console.log('posse plugin init (nothing loaded)');

    var resizeIframe = function (obj) {
        var height = (obj.contentWindow.document.body.scrollHeight + 15) + 'px'; // add some pixels to avoid scrollbar
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
                } else if($iframe.data('reload-on-url')) {
                    // reload only if iframe matches expected url
                    if (iframe.contentWindow.location.pathname == $iframe.data('reload-on-url')) {
                        window.location.assign(iframe.contentWindow.location.href);
                    }
                } else {
                    console.log('iframe.contentWindow.location.href', iframe.contentWindow.location.href);
                    window.location.assign(iframe.contentWindow.location.href);
                    //window.document.location.reload();
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

    $('.map[data-url]').each(function (a, map) {
        var $map = $(map);
        cartodb.createVis(map, $map.data('url'));
    });


    // $('.user-calendar').fullCalendar({});
});
