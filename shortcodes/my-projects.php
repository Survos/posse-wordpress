<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function my_posse_projects($atts, $content = '')
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
            'shortcode' => 'my-projects',
            'content' => $content,
            'data'      => [
                'user'    => Posse::getCurrentSymfonyUser(),
                'wp_user' => get_currentuserinfo(),
            ]
        ]);

    return $return;
}