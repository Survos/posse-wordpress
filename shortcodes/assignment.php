<?php


/**
<<<<<<< Updated upstream
 * return  project form
 * @param $atts
 * @return string
 */

function posse_assignment($atts, $content='')
{
    extract(
        shortcode_atts(
            [
                'membertypecode' => 'personal',
                'waveid' => '0',
                'exists_html' => "",
                'new_html' => ""
            ],
            $atts
        )
    );


    // dump($atts, $content);
    $user = Posse::getSymfonyUser();

    // todo: permissions
    if (!$wave = \Posse\SurveyBundle\Model\Wave\WaveQuery::create()->findPk($waveid))
    {
        return sprintf("Shortcode error: Cannot find wave %s", $waveId);
    }
    if (empty($exists_html)) {
        // $exists_html =
    }

    $category = $wave->getJob()->getCategory();

    $mt = $category->getMemberType();

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'assignment',
        'content'   => $content,
        'data'      => [
            'wave' => $wave,
            'memberType' => $mt,
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}
