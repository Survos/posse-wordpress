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
    try {
        $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
            'shortcode' => 'user',
            'content'   => $content,
            'data'      => [
                'project' => $project,
                'trackedMember' => $project->getTrackedMember($user),
                'user' => $user !== 'anon.' ? $user : null,
            ]
        ]);
    } catch (\Exception $e) {
        dump($e->getMessage(), $content);
        die();
    }

    return $return;
}