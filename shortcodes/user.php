<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_user($atts, $content = null)
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
        'content'   => $content,
        'data'      => [
            'user'    => Posse::getCurrentSymfonyUser(),
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}