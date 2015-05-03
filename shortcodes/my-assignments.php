<?php

/**
 * return member data
 * @param $atts
 * @return string
 */
function my_posse_assignments($atts, $content = '')
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );

    $user = Posse::getSymfonyUser();
    if (!$mt = \Posse\SurveyBundle\Model\Type\MemberTypeQuery::create()->findOneByCode($code='personal')) // should be an attribute
    {
        return sprintf("Shortcode error: invalid memberType %s", $code);
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-assignments',
        'content'   => $content,
        'data'      => [
            'category' => \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByCode('single'),  // should be an attribute!
            'memberType' => $mt,
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
            'user'    => $user,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}