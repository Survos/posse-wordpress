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
                'categorycode' => 'single',
                'autocreate' => false
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
    if (!is_object($user)) // really trying to avoid 'anon'
    {
        return "Please log in or create an account to continue.";
    }

    if (!$category = \Posse\SurveyBundle\Model\CategoryQuery::create()->findOneByCode($categorycode))
    {
        return sprintf("Shortcode error: invalid categorycode %s", $categorycode);
    }
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();

    $mt = $category->getMemberType();
    $member = $mt->memberQuery($project)->filterByUser($user)->findOne();


    if (!$member and $autocreate) {
        {
            // this automatically creates a member if it doesn't exist
            $member = $this->getProject()->getAutoCreateMembers($this->getUser(), $category);
            $member
                ->save();
        }
    }

    $return = Posse::renderTemplate('PosseServiceBundle:Wordpress:shortcode.html.twig', [
        'shortcode' => 'my-assignments',
        'content'   => $content,
        'data'      => [
            'category' => $category,  // should be an attribute!
            'memberType' => $mt,
            'member'     => $member,
            'project' => $project,
            'user'    => $user,
            'wp_user' => get_currentuserinfo(),
        ]
    ]);

    return $return;
}