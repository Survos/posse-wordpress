<?php

function posse_assignments($atts, $content = '')
{
    extract(
        shortcode_atts(
            [
                'membertypecode'     => 'personal',
                'waveid'             => "0",
                'exists_text'        => "",
                'new_html'           => "",
                "a_class"            => "btn btn-success",
                "take_text"          => "Start Survey",
                'not_logged_in_text' => "You must be logged in to take this survey"
                //@todo that message will show for logged in users which are not members
            ],
            $atts
        )
    );


    // dump($atts, $content);
    $user = Posse::getSymfonyUser();

    // todo: permissions
    if ($waveid && $wave = \Posse\SurveyBundle\Model\Wave\WaveQuery::create()->findPk($waveid)) {
        $category = $wave->getJob()->getCategory();
    } elseif ($membertypecode) {
        $category = \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByMemberTypeCode($membertypecode);
    } else {
        return sprintf("Shortcode error: Cannot find wave [%s], %s", $waveid, json_encode($atts));
    }

    if (empty($exists_html)) {
        // $exists_html =
    }


    $mt = $category->getMemberType();
    $member = ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null;

    try {
        $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
            'shortcode' => 'assignments',
            'content'   => $content,
            'data'      => [
                'wave'               => $wave,
                'exists_text'        => $exists_text,
                'take_text'          => $take_text,
                'a_class'            => $a_class,
                'not_logged_in_text' => $not_logged_in_text,
                'memberType'         => $mt,
                'member'             => $member, // missing $project!
                'wp_user'            => get_currentuserinfo(),
            ]
        ]);
    } catch (\Exception $e) {
        return $e->getMessage();
    }

    return $return;
}
