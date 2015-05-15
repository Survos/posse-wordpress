<?php

/**
 * return ct data
 *
 * @param $atts
 *
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

    $user = Posse::getSymfonyUser();
    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'user',
        'content'   => $content,
        'data'      => [
            'user' => $user !== 'anon.' ? $user : null,
        ]
    ]);

    return $return;
}