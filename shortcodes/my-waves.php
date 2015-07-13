<?php

/**
 * return member data
 * @param $atts
 * @return string
 */
function my_posse_waves($atts, $content = '')
{

    extract(
        shortcode_atts(
            [
                'categorycode' => 'single'
            ],
            $atts
        )
    );
    /*
    $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
    $resolver->setDefaults([
        'categorycode' => 'single'
    ]);
    $atts = $resolver->resolve($atts);
    $categoryCode = $atts['categorycode'];
    */

    $user = Posse::getSymfonyUser();
    if (!$category = \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByCode($categorycode))
    {
        return sprintf("Shortcode error: invalid categoryCode %s", $categorycode);
    }

    $project = Posse::getProjectManager()->getProject();
    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-waves',
        'content'   => $content,
        'data'      => [
            'category' => $category,  // should be an attribute!
            'memberType' => ($mt = $category->getMemberType()),
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery($project)->filterByUser($user)->findOne() : null,
            'user'    => $user,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}
