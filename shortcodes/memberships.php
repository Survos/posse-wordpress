<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_memberships($atts, $content = null)
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'memberships',
        'content'   => $content,
        'data'      => [
            'user'    => Posse::getSymfonyUser(),
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}