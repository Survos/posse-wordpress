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
//        add_filter('query_vars', ['Posse', 'posse_custom_query_vars']);
//        add_filter('rewrite_rules_array', ['Posse', 'posse_theme_functionality_urls']);
        self::initSymfony();
        require_once(POSSE__PLUGIN_DIR.'shortcodes/ct.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/user.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/memberships.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/job.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/jobs.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/survey.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/surveys.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/my-projects.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/projects.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/project_attribute.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/login-form.php');
        require_once(POSSE__PLUGIN_DIR.'shortcodes/user-calendar.php');
        add_shortcode('project', 'posse_project_attribute');
        add_shortcode('my-projects', 'my_posse_projects');
        add_shortcode('projects', 'posse_projects');
        add_shortcode('jobs', 'posse_jobs');
        add_shortcode('job', 'posse_job');
        add_shortcode('surveys', 'posse_surveys');
        add_shortcode('survey', 'posse_survey');
        add_shortcode('ct', 'posse_ct');
        add_shortcode('user', 'posse_user');
        add_shortcode('memberships', 'posse_memberships');
        add_shortcode('login-form', 'posse_login_form');
        add_shortcode('user-calendar', 'posse_user_calendar');

        add_action('wp_enqueue_scripts', ['Posse', 'load_assets']);

        add_action('wp_signup_location', 'posse_register_add_project_code');

        function posse_register_add_project_code($link)
        {
            $site = get_blog_details();
            $parts = explode('.', $site->domain);
            $projectCode = reset($parts);

            return $link."?project=".$projectCode;
        }

    }

    public static function load_assets()
    {
        // load fullcalendar
        wp_enqueue_style('fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.0/fullcalendar.min.css');
        wp_enqueue_style('fullcalendar-print', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.0/fullcalendar.print.css');
        wp_enqueue_script('fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.0/fullcalendar.min.js', ['jquery', 'moment-locales']);

        // main plugin assets
        wp_enqueue_script('moment-locales', '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js');
        wp_enqueue_script('posse-main', plugin_dir_url(__FILE__).'js/main.js');
    }

    public static function syncUser(WP_User $user, $password = '')
    {
        /** @var \Posse\UserBundle\Manager\UserManager $um */
        $um = self::symfony('survos.user_manager');

        $um->createUserFromWp($user, $password);

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
            'visitor',
            'role 2',
            'role 3'
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

add_action('signup_extra_fields', 'myplugin_add_registration_fields');

function myplugin_add_registration_fields()
{

    //Get and set any values already sent
    $posse_user_role = (isset($_POST['posse_user_role'])) ? $_POST['posse_user_role'] : '';
    ?>

    <div class="form-group">
        <label for="posse_user_role"><?php _e('Project role', 'myplugin_textdomain') ?>
        </label>
        <select type="text" name="posse_user_role" id="posse_user_role" class="input"
                value="<?php echo esc_attr(stripslashes($posse_user_role)); ?>">
            <?php foreach (Posse::getProjectRoles() as $role): ?>
                <option value="<?php echo $role ?>"><?php echo $role ?></option>
            <?php endforeach ?>
        </select>
    </div>


<?php
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