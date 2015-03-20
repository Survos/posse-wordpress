<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_membership($atts, $content = null)
{
    extract(
        shortcode_atts(
            [
                'code' => ''
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'membership',
        'content'   => $content,
        'data'      => [
            'user'       => $user = Posse::getCurrentSymfonyUser(),
            'wp_user'    => get_currentuserinfo(),
            'memberType' => $mt = \Posse\SurveyBundle\Model\Type\MemberTypeQuery::create()->findOneByCode($code),
            'member'     => $user ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
        ]
    ]);

    return $return;
}