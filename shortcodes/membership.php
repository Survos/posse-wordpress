<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_membership($atts, $content = null)
{
    // why not use OptionsResolver here?
    extract(
        shortcode_atts(
            [
                'code' => ''
            ],
            $atts
        )
    );

    if (!$mt = \Posse\SurveyBundle\Model\Type\MemberTypeQuery::create()->findOneByCode($code))
    {
        return sprintf("Shortcode error: invalid memberType %s", $code);
    }
    $user = Posse::getSymfonyUser();
    $return =   Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'membership',
        'content'   => $content,
        'data'      => [
            'user'       => $user,
            'wp_user'    => get_currentuserinfo(),
            'memberType' => $mt,
            'member'     => ($mt && $user) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
        ]
    ]);

    return $return;
}