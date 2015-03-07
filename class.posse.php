<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        add_action('parse_request', ['Posse', 'posse_custom_requests']);
        add_filter('rewrite_rules_array', ['Posse', 'posse_theme_functionality_urls']);
        self::initSymfony();
        require_once(POSSE__PLUGIN_DIR.'shortcodes/shortcodes.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/ct.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/user.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/projects.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/login_form.php');
        add_shortcode('project', 'posse_project_attribute');
        add_shortcode('projects', 'posse_projects');
        add_shortcode('jobs', 'posse_jobs');
        add_shortcode('job', 'posse_job');
        add_shortcode('surveys', 'posse_surveys');
        add_shortcode('survey', 'posse_survey');
        add_shortcode('ct', 'posse_ct');
        add_shortcode('user', 'posse_user');
        add_shortcode('login_form', 'posse_login_form');
    }

    public static function initSymfony()
    {
        //            require_once( ABSPATH . 'wp-includes/ms-functions.php' );
        $loader = require_once __DIR__.'/../../../../app/bootstrap.php.cache';

        // Load application kernel
        require_once __DIR__.'/../../../../app/AppKernel.php';

        $sfKernel = new AppKernel('dev', true);
        $sfKernel->loadClassCache();
        $sfKernel->boot();
        // Add Symfony container as a global variable to be used in Wordpress
        $sfContainer = $sfKernel->getContainer();

        if (true === $sfContainer->getParameter('kernel.debug', false)) {
            Debug::enable();
        }

        $sfContainer->enterScope('request');

        /** @var \Posse\SurveyBundle\Services\ProjectManager $pm */
        $pm = $sfContainer->get('survos_survey.project_manager');
        $sfRequest = Request::createFromGlobals();
        $sfResponse = $sfKernel->handle($sfRequest);
//        $sfResponse->send();

        $site = get_blog_details();
        $parts = explode('.', $site->domain);
        $projectCode = reset($parts);
        $project = $pm->getProjectByName($projectCode);

        self::symfony($sfContainer);
        if ($project) {
            $pm->setProject($project);
        }

        // try to authenticate user
        if (is_user_logged_in()) {
            /** @var WP_User $current_user */
            $current_user = wp_get_current_user();
            $email = $current_user->get('user_email');
            $symfonyUser = self::getSymfonyUser();
            if (!$symfonyUser || $symfonyUser == 'anon.') {
                self::getWpService()->authenticateUserByEmail($email);
            }
        }



//      $sfRequest = Request::createFromGlobals();
//      $sfResponse = $sfKernel->handle($sfRequest);
//      $sfResponse->send();
//
//      $sfKernel->terminate($sfRequest, $sfResponse);
    }

    /**
     * Retrieves or sets the Symfony Dependency Injection container
     *
     * @param ContainerInterface|string $id
     *
     * @return mixed
     */
    public static function symfony($id)
    {
        static $container;
        if ($id instanceof ContainerInterface) {
            $container = $id;
            return;
        }
        if (!$container) {
            return null;
        }
        return $container->get($id);
    }

    /**
     * get project manager service
     */
    public static function getProjectManager()
    {
        return self::symfony('survos_survey.project_manager');
    }

    /**
     * @return \Posse\ServiceBundle\Services\WordpressService
     */
    private static function getWpService()
    {
        $svc = self::symfony('posse.wordpress');
        if (!$svc) {
            throw new Exception('Couldn\'t load Posse Wordpress service');
        }
        return $svc;
    }
    /**
     * @return User
     */
    private static function getSymfonyUser()
    {
        return self::getWpService()->getCurrentUser();
    }

    /**
     * get project manager service
     */
    public static function renderTemplate($template, $atts = [])
    {
        return self::symfony('twig')->render($template, $atts);
    }

    /**
     * get project manager service
     */
    public static function getJob($code)
    {
        return self::symfony('survos.service.job')->getJob($code);
    }

    /**
     * get ct object
     */
    public static function getCt($code)
    {
        $ct = self::symfony('survos.clinical_trials')->getCt($code);
        $html = self::symfony('templating')->render("SurvosClinicalTrialsBundle:Trial:_ct_view.html.twig", ['ct' => $ct]);
        return $html;
    }

    /**
     * get survey
     */
    public static function getSurvey($code)
    {
        return self::symfony('survos.service.survey')->getSurvey($code);
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
    /**
     * get surveys
     */
    public static function getSurveys()
    {
        /** @var \Posse\SurveyBundle\Model\Project $project */
        $project = self::getProjectManager()->getProject();
        if (!$project) {
            echo "!Project not found!";
        }
        return $project->getSurveys();
    }


    /**
     * add new rewrite rule to handle api calls from symfony
     */
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
     * @return null
     */
    public static function getCurrentUserInfo()
    {
        if (is_user_logged_in()) {
            /** @var WP_User $current_user */
            $current_user = wp_get_current_user();
            return self::getWpService()->getCurrentUserInfo($current_user);
        }

        return "Not logged in";
    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation()
    {
    }

    /**
     * Removes all connection options
     * @static
     */
    public static function plugin_deactivation()
    {
        //tidy up
    }
}

