<?php

/**
 * return member data
 * @param $atts
 * @return string
 */
function my_posse_assignments($atts, $content = '')
{
    extract(
        $atts = shortcode_atts(
            [
                'categorycode' => 'single'
            ],
            $atts
        )
    );
    /*
    $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
    $resolver->setDefaults([
        'categoryCode' => 'single'
    ]);
    $atts = $resolver->resolve($options);
    $categoryCode = $atts['categorycode'];
    */

    $user = Posse::getSymfonyUser();
    if (!$category = \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByCode($categorycode))
    {
        return sprintf("Shortcode error: invalid categorycode %s", $categorycode);
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-assignments',
        'content'   => $content,
        'data'      => [
            'category' => $category,  // should be an attribute!
            'memberType' => ($mt = $category->getMemberType()),
            'member'     => ($mt && is_object($user)) ? $mt->memberQuery()->filterByUser($user)->findOne() : null, // missing $project!
            'user'    => $user,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}