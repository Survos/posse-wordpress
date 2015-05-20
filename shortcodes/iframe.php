<?php


/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_iframe($atts)
{
    extract(
        shortcode_atts(
            [
                'url' => 'http://www.google.com'
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'iframe',
        'content'   => null,
        'data'      => [
            'url' => $url
        ]
    ]);

    return $return;
}

