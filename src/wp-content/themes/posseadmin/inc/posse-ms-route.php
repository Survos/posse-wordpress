<?php
/**
 * add custom route to handle new blog creation
 */

function posse_custom_query_vars($vars)
{
    $vars[] = 'api_action';
    $vars[] = 'api_key';
    $vars[] = 'blog_domain';
    $vars[] = 'blog_title';
    $vars[] = 'blog_user_name';
    $vars[] = 'blog_user_pass';
    $vars[] = 'blog_user_email';
    return $vars;
}

function posse_theme_functionality_urls()
{

    add_rewrite_rule(
        '^api/?',
        'index.php',
        'top'
    );

}

/**
 * handles custom api call from symfony to create new blog
 * @param $wp
 */
function posse_custom_requests($wp)
{

    $valid_actions = ['possecreateblog', 'possecheckblog'];

    if (
        !empty($wp->query_vars['api_action'])
        && in_array($wp->query_vars['api_action'], $valid_actions)
        && ($wp->query_vars['api_key'] == API_KEY)
    ) {
        switch ($wp->query_vars['api_action']) {
            case 'possecreateblog':
                posseCreateBlog($wp->query_vars);
                break;
            case 'possecheckblog':
                posseCheckBlog($wp->query_vars);
                break;
        }

    }

}

/**
 * create new empty blog
 * @param $vars
 */
function posseCreateBlog($vars)
{
    $user = get_user_by('email', $vars['blog_user_email']);
    if ($user === false) {
        $newUserId = wpmu_create_user($vars['blog_user_name'], $vars['blog_user_pass'], $vars['blog_user_email']);
    } else {
        $newUserId = $user->ID;
    }
    $id = wpmu_create_blog($vars['blog_domain'], '/', $vars['blog_title'], $newUserId);

    // return json with info about success
    $result = [
        'success' => is_int($id),
        'error'   => !is_int($id) ? $id : '',
    ];
    if ($result['success']) {
//        add_blog_option($id, 'blogname', $vars['blog_title']);
        $args = [
            'domain' => $vars['blog_domain'],
            'path'   => '/',
        ];
        $resultTmp = get_blog_details($args, true);
        if (!$resultTmp) {
            $resultTmp = new StdClass;
        }
        $resultTmp->success = $result['success'];
        $result = $resultTmp;
    }
    header('Content-Type: application/json');
    die(json_encode($result));
}

/**
 * checks status of the blog for project
 * @param $vars
 */
function posseCheckBlog($vars)
{
    $args = [
        'domain' => $vars['blog_domain'],
        'path'   => '/',
    ];
    $result = get_blog_details($args, true);
    header('Content-Type: application/json');
    die(json_encode($result));
}

