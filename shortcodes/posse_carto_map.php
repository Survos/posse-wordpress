<?php


/**
 * simply return current project attribute
 * @param $atts
 * @return string
 */
function posse_carto_map($atts, $content='')
{
    extract(
        shortcode_atts(
            [
                'attribute' => 'account',
                'account' => 'abc',
                'map'=> '123',
                'width' => 1024
            ],
            $atts
        )
    );
    // dump($atts, $attribute, $account, $map, $width); die();

    // todo: permissions
    if (!$mt = \Posse\SurveyBundle\Model\Carto\CartoMapQuery::create()
        ->joinWith('CartoAccount')
        ->useCartoAccountQuery()
            ->filterByAccountName($account)
        ->endUse()
        ->filterByName($map)->findOne())
    {
        return sprintf("Shortcode error: Cannot find carto map %s", $account, $map);
    }


    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'cartomap',
        'content'   => $content,
        'data'      => [
            'cartoMap' => $mt,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}
