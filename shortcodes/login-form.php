<?php

/**
 * return ct data
 *
 * @param $atts
 *
 * @return string
 */
function posse_login_form($atts)
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );

    /** @var \Posse\SurveyBundle\Services\ProjectManager $pm */
    $pm = Posse::getProjectManager();
    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'login',
        'content'   => null,
        'data'      => [
            'pm' => Posse::getProjectManager()
        ]
    ]);
    return $return;
}