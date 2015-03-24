<?php



function posse_add_registration_fields()
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
 * override welcome email
 * @param $user_id
 * @param $password
 * @param string $meta
 * @return bool
 */
function posse_wpmu_welcome_user_notification($user_id, $password, $meta = '') {
    global $current_site;

    /*
     * @todo add custom field/post with template here
     */
    $welcome_email = get_site_option( 'welcome_user_email' );

    $user = new WP_User($user_id);

    $welcome_email = apply_filters( 'update_welcome_user_email', $welcome_email, $user_id, $password, $meta);

    // Get the current blog name
    $blogname = get_option( 'blogname' );
    $welcome_email = str_replace( 'SITE_NAME', $blogname, $welcome_email );

    $welcome_email = str_replace( 'USERNAME', $user->user_login, $welcome_email );
    $welcome_email = str_replace( 'PASSWORD', $password, $welcome_email );
    $welcome_email = str_replace( 'LOGINLINK', get_bloginfo('wpurl'), $welcome_email );

    $admin_email = get_site_option( 'admin_email' );

    $from_name = get_site_option( 'site_name' ) == '' ? 'WordPress' : esc_html( get_site_option( 'site_name' ) );
    $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
    $message = $welcome_email;

    $subject = apply_filters( 'update_welcome_user_subject', sprintf(__('New %1$s User: %2$s'), $blogname, $user->user_login) );
    wp_mail($user->user_email, $subject, $message, $message_headers);

    return false; // make sure wpmu_welcome_user_notification() doesn't keep running
}

/**
 * Problem: WordPress MultiSite sends user signup mails from the main site. This is a problem when using domain mapping functionality as the sender is not the same domain as expected when creating a new user from a blog with another domain.
 * Solution: Change the default user notification mail from using the main network admin_email and site_name to the blog admin_email & blogname
 *
 * @author Daan Kortenbach
 * @link http://daankortenbach.nl/wordpress/filter-wpmu_signup_user_notification/
 */
function posse_wpmu_signup_user_notification($user, $user_email, $key, $meta = '')
{
    $blog_id = get_current_blog_id();
    // Send email with activation link.
    $admin_email = get_option('admin_email');
    $projectCode = isset($_POST['project_code']) ? $_POST['project_code'] : '';
    $blog = get_blog_details($projectCode);
    // if blog found
    if ($blog) {
        $siteUrl = $blog->siteurl."/wp-activate.php?key=$key";
        $from_name = $blog->blogname." registration";
    } else {
        $siteUrl = site_url("wp-activate.php?key=$key");
        $from_name = get_option('blogname') == '' ? 'WordPress' : esc_html(get_option('blogname'));
    }

    $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n"."Content-Type: text/plain; charset=\"".get_option('blog_charset')."\"\n";

    $message = sprintf(
        apply_filters('wpmu_signup_user_notification_email',
                      __("To activate your user, please click the following link:\n\n%s
                       \n\nAfter you activate, you will receive *another email* with your login.\n\n"),
                      $user, $user_email, $key, $meta
        ),
        $siteUrl
    );

    $subject = sprintf(
        apply_filters('wpmu_signup_user_notification_subject',
                      __('[%1$s] Activate %2$s'),
                      $user, $user_email, $key, $meta
        ),
        $from_name,
        $user
    );
    wp_mail($user_email, $subject, $message, $message_headers);

    return false;
}

function posse_before_signup_form()
{
    echo '<div class="center-block">';
}

function posse_signup_finished()
{
    echo "</div>";
}