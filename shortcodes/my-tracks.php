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
                'maps' => '',
            ],
            $atts
        )
    );

    $user = Posse::getSymfonyUser();
    if (!$mt = \Posse\SurveyBundle\Model\Type\MemberTypeQuery::create()->findOneByCode('tracked')) // ack!
    {
        return sprintf("Shortcode error: invalid memberType %s", $code);
    }

    $mapIds = $mapArray = [];
    foreach ( explode(',', $maps) as $mapAddr) {
        list($name, $account) = explode('@', $mapAddr);
        if ($name && $account)
        {
            if (!$map = \Posse\SurveyBundle\Model\Carto\CartoMapQuery::create()
                ->useCartoAccountQuery()
                ->filterByAccountName(trim($account))
                ->endUse()
                ->filterByName(trim($name))
                ->findOne()
            ) {
                return sprintf("No map '%s' in carto account %s", $name, $account);
            }
            $mapArray[] = $map;
            $mapIds[] = $map->getId();
        }
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-tracks',
        'content'   => $content,
        'data'      => [
            'memberType' => $mt,
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
            'user'    => $user,
            'maps'    => $mapArray,
            'mapIds' => $mapIds,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}