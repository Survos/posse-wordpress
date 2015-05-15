<?php


/**
 * return  project form
 *
 * @param $atts
 *
 * @return string
 */
function posse_register($atts)
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
        'shortcode' => 'register',
        'content'   => null,
        'data'      => [
            'pm' => Posse::getProjectManager()
        ]
    ]);

    return $return;
}

