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

    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if (!$project) {
        return "!Project not found!";
    }

    $user = Posse::getSymfonyUser();

    if ($content) {
        $content = html_entity_decode($content);
        $content = Posse::fixContentQuotes($content);
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'user',
        'content'   => $content,
        'data'      => [
            'user' => $user !== 'anon.' ? $user : null,
        ]
    ]);

    return $return;
}