<?php


/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_assignment($atts)
{
    extract(
        shortcode_atts(
            [
                'memberCode' => '',
                'waveId' => ''
            ],
            $atts
        )
    );

    /** @var \Posse\SurveyBundle\Services\ProjectManager $pm */
    $pm = Posse::getProjectManager();
    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'assignment',
        'content'   => $content,
        'data'      => [
        ]
    ]);

    return $return;
}

