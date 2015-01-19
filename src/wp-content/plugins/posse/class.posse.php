<?php

/**
 * Class Posse
 */
class Posse
{
    private static $initiated = false;

    public static function install()
    {

    }

    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks()
    {
        self::$initiated = true;
        add_filter('query_vars', ['Posse', 'posse_custom_query_vars']);
        add_action('init', ['Posse', 'posse_theme_functionality_urls']);
        add_action('parse_request', ['Posse', 'posse_custom_requests']);

    }


    public static function posse_custom_query_vars($vars)
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

    public static function posse_theme_functionality_urls()
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
    public static function posse_custom_requests($wp)
    {

        $valid_actions = ['possecreateblog', 'possecheckblog'];

        if (
            !empty($wp->query_vars['api_action'])
            && in_array($wp->query_vars['api_action'], $valid_actions)
            && ($wp->query_vars['api_key'] == API_KEY)
        ) {
            switch ($wp->query_vars['api_action']) {
                case 'possecreateblog':
                    self::posseCreateBlog($wp->query_vars);
                    break;
                case 'possecheckblog':
                    self::posseCheckBlog($wp->query_vars);
                    break;
            }

        }

    }

    /**
     * create new empty blog
     * @param $vars
     */
    private static function posseCreateBlog($vars)
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
    private static function posseCheckBlog($vars)
    {
        $args = [
            'domain' => $vars['blog_domain'],
            'path'   => '/',
        ];
        $result = get_blog_details($args, true);
        header('Content-Type: application/json');
        die(json_encode($result));
    }


    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation( ) {
        //tidy up
    }
}
