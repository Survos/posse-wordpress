<?php


/**
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
                'waveid' => "0",
                'exists_text' => "",
                'new_html' => "",
                "a_class" => "btn btn-success",
                "take_text" =>"Start Survey",
                'not_logged_in_text' => "You must be logged in to take this survey"
            ],
            $atts
        )
    );


    // dump($atts, $content);
    $user = Posse::getSymfonyUser();

    // todo: permissions
    if (!$wave = \Posse\SurveyBundle\Model\Wave\WaveQuery::create()->findPk($waveid))
    {
        return sprintf("Shortcode error: Cannot find wave [%s], %s", $waveid, json_encode($atts));
    }
    if (empty($exists_html)) {
        // $exists_html =
    }

    $category = $wave->getJob()->getCategory();

    $mt = $category->getMemberType();

    try {
        $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
            'shortcode' => 'assignment',
            'content'   => $content,
            'data'      => [
                'wave' => $wave,
                'exists_text' => $exists_text,
                'take_text' => $take_text,
                'a_class' => $a_class,
                'not_logged_in_text' => $not_logged_in_text,
                'memberType' => $mt,
                'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
                'wp_user' => get_currentuserinfo(),
            ]
        ]);
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    return $return;
}
