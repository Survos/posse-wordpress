<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_projects($atts)
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
        'shortcode' => 'projects',
        'data'      => [
            'projects' => $pm->getAllActiveProjects(),
            'pm'       => $pm
        ]
    ]);

    return $return;
}