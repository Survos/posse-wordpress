<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_user($atts)
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'user',
        'data'     => [
            'user'    => Posse::getCurrentSymfonyUser(),
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}