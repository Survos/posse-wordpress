<?php


/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_iframe($atts)
{
    extract(
        shortcode_atts(
            [
                'url' => 'http://www.survos.com',
                'height' => '0',
                'width' => '0',
                'class' => ''
                'div_class' => ''
            ],
            $atts
        )
    );

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'iframe',
        'content'   => null,
        'data'      => [
            'url' => $url,
            'height' => $height,
            'width' => $width,
            'class' => $class,
            'div_class' => $div_clas
        ]
    ]);

    return $return;
}

