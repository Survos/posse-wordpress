<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;

// registration related functions
require_once(POSSE__PLUGIN_DIR.'inc/comments.php');
require_once(POSSE__PLUGIN_DIR.'inc/registration.php');
require_once(POSSE__PLUGIN_DIR.'inc/post-types.php');
require_once(POSSE__PLUGIN_DIR.'inc/custom-fields.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/ct.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/user.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/memberships.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/membership.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/job.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/jobs.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/register.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/survey.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/surveys.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/my-projects.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/projects.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/project_attribute.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/login-form.php');
require_once(POSSE__PLUGIN_DIR.'shortcodes/user-calendar.php');

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
//        add_filter('query_vars', ['Posse', 'posse_custom_query_vars']);
//        add_filter('rewrite_rules_array', ['Posse', 'posse_theme_functionality_urls']);
        self::initSymfony();

        add_shortcode('project', 'posse_project_attribute');
        add_shortcode('my-projects', 'my_posse_projects');
        add_shortcode('projects', 'posse_projects');
        add_shortcode('jobs', 'posse_jobs');
        add_shortcode('job', 'posse_job');
        add_shortcode('surveys', 'posse_surveys');
        add_shortcode('survey', 'posse_survey');
        add_shortcode('register', 'posse_register');
        add_shortcode('ct', 'posse_ct');
        add_shortcode('user', 'posse_user');
        add_shortcode('memberships', 'posse_memberships');
        add_shortcode('membership', 'posse_membership');
        add_shortcode('login-form', 'posse_login_form');
        add_shortcode('user-calendar', 'posse_user_calendar');

        add_action('wp_enqueue_scripts', ['Posse', 'load_assets']);

        add_action('wp_signup_location', 'posse_register_add_project_code');

        // register custom post types
        posse_create_post_types();

        // register ACF (custom fields)
        posse_create_custom_fields();

        // reg form fields
        add_action('signup_extra_fields', 'posse_add_registration_fields');
        // reg form layout
        add_filter('before_signup_form', 'posse_before_signup_form', 10, 4);
        add_filter('signup_finished', 'posse_signup_finished', 10, 4);
        // activation email
        add_filter('wpmu_signup_user_notification', 'posse_wpmu_signup_user_notification', 10, 4);
        // welcome email
        add_filter('wpmu_welcome_user_notification', 'posse_wpmu_welcome_user_notification', 10, 3);

        // disable comments
        add_action('admin_init', 'df_disable_comments_post_types_support');
        add_filter('comments_open', 'df_disable_comments_status', 20, 2);
        add_filter('pings_open', 'df_disable_comments_status', 20, 2);
        add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
        add_action('admin_menu', 'df_disable_comments_admin_menu');
        add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
        add_action('admin_init', 'df_disable_comments_dashboard');
        add_action('init', 'df_disable_comments_admin_bar');

        function posse_register_add_project_code($link)
        {
            $site = get_blog_details();
            $parts = explode('.', $site->domain);
            $projectCode = reset($parts);

            return $link."?project=".$projectCode;
        }

    }

    /**
     * load full calendar styles + deps
     */
    public static function load_calendar_assets()
    {
        /*
        wp_enqueue_style('fullcalendar', '/components/fullcalendar/fullcalendar.css');
//        wp_enqueue_style('fullcalendar-print', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.0/fullcalendar.print.css');
        wp_enqueue_script('fullcalendar', '/components/fullcalendar/fullcalendar.js', ['jquery', 'moment-tz']);
        */
        // main plugin assets
        wp_enqueue_script('moment', '/components/moment/moment.js');
        wp_enqueue_script('moment-tz', '/components/moment-timezone/moment-timezone-with-data-2010-2020.min.js', ['moment']);
    }

    /**
     * load plugin assets
     */
    public static function load_assets()
    {
        wp_enqueue_script('posse-main', plugin_dir_url(__FILE__).'js/main.js');
        wp_enqueue_style('posse-main', plugin_dir_url(__FILE__).'css/main.css');
    }

    public static function syncUser(WP_User $user, $password = '')
    {
        /** @var \Posse\UserBundle\Manager\UserManager $um */
        $um = self::symfony('survos.user_manager');

        $um->createUserFromWp($user, $password);

    }

    public static function initSymfony()
    {
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
            /** @var \Posse\UserBundle\Propel\User $symfonyUser */
            $symfonyUser = self::getSymfonyUser();
            if (!$symfonyUser || $symfonyUser == 'anon.'||($symfonyUser && $symfonyUser->getEmail() != $email)) {
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
    public static function symfony($id, $parameter = false, $def = '')
    {
        static $container;
        if ($id instanceof ContainerInterface) {
            $container = $id;
            return;
        }
        if (!$container) {
            return null;
        }
        if ($parameter !== false) {
            return $container->getParameter($parameter, $def);
        } else {
            return $container->get($id);
        }
    }

    public static function getParameter($param, $def = '')
    {
        return self::symfony(null, $param, $def);
    }

    public static function getBlogFullDomain($slug)
    {
        $dom = self::getParameter('wordpress.master_domain');
        return $slug.".".$dom;
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
     * get surveys
     */
    public static function getProjectRoles()
    {
        return [
            'this-isnt-working-yet',
            'visitor',
            'participant',
            'field worker',
            'admin',
        ];
    }

    /**
     * @return \Posse\UserBundle\Propel\User
     */
    public static function getCurrentSymfonyUser()
    {
        return self::getWpService()->getCurrentUser();
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

/**
 * Add a hidden field with the theme's value
 */
function posse_theme_hidden_fields()
{ ?>

    <?php
    $project = isset($_GET['project']) ? $_GET['project'] : '';
    ?>
    <input type="hidden" name="project_code" value="<?php echo $project; ?>">
<?php }

add_action('signup_hidden_fields', 'posse_theme_hidden_fields');

function posse_add_signup_meta($result)
{

    return [
        'posse_user_role' => $_POST['posse_user_role'],
        'project_code'    => $_POST['project_code'],
    ];
}

add_filter('add_signup_meta', 'posse_add_signup_meta');

/**
 * @param $user_id
 * @param $password
 * @param $meta
 */
function posse_wpmu_activate_user($user_id, $password, $meta)
{
    if (isset($meta['project_code']) && isset($meta['posse_user_role'])) {
        $project_role = get_user_meta($user_id, 'project_role', true);
        if (!$project_role) {
            $project_role = [];
        }
        $project_role[$meta['project_code']] = $meta['posse_user_role'];
        update_user_meta($user_id, 'project_role', $project_role);

        $blog = get_blog_details(['domain' => Posse::getBlogFullDomain($meta['project_code'])]);

        if ($blog) {
            add_user_to_blog($blog->blog_id, $user_id, 'subscriber');
            ?>
            <script>window.location.replace("<?php echo $blog->siteurl ?>");</script><?php
        }

        unset($meta['project_code']);
        unset($meta['posse_user_role']);
    }
    // update other meta fields
    foreach ($meta as $key => $val) {
        update_user_meta($user_id, $key, $val);
    }

    Posse::syncUser(get_userdata($user_id), $password);
}

add_filter('wpmu_activate_user', 'posse_wpmu_activate_user', 1, 3);