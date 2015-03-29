<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_ct($atts, $content = null)
{
    extract(
        shortcode_atts(
            [
                'code' => ''
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'ct',
        'content'   => $content,
        'data'      => [
            'ct' => Posse::getCt($code)
        ]
    ]);

    return $return;
}