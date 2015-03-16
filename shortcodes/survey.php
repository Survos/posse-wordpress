<?php


/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_survey($atts, $content = null)
{
    extract(
        shortcode_atts(
            [
                'code' => ''
            ],
            $atts
        )
    );
    $code = get_field('survos_code');
    $id = get_field('survos_id');

    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();

    if (!$project) {
        echo "!Project not found!";
    }

    if (!$code) {
        echo "!no survey code given!";
    }
    $survey = Posse::getSurvey($code);

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'survey',
        'content'   => $content,
        'data'      => [
            'survey'    => $survey
        ]
    ]);
    return $return;
}

