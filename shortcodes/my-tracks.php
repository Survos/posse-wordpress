<?php

/**
 * return member data
 * @param $atts
 * @return string
 */
function my_posse_tracks($atts, $content = '')
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );

    $user = Posse::getSymfonyUser();
    if (!$mt = \Posse\SurveyBundle\Model\Type\MemberTypeQuery::create()->findOneByCode('personal')) // ack!
    {
        return sprintf("Shortcode error: invalid memberType %s", $code);
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-tracks',
        'content'   => $content,
        'data'      => [
            'memberType' => $mt,
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
            'user'    => $user,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}