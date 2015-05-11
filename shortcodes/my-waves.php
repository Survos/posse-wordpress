<?php

/**
 * return member data
 * @param $atts
 * @return string
 */
function my_posse_waves($atts, $content = '')
{
    extract(
        $options = shortcode_atts(
            [
            ],
            $atts
        )
    );
    var_dump($options);
    $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
    $resolver->setDefaults([
        'categoryCode' => 'single'
    ]);
    $atts = $resolver->resolve($options);
    $categoryCode = $atts['categoryCode'];

    $user = Posse::getSymfonyUser();
    if (!$category = \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByCode($categoryCode))
    {
        return sprintf("Shortcode error: invalid categoryCode %s", $categoryCode);
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-waves',
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