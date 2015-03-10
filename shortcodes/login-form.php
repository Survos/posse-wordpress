<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_login_form($atts)
{
    extract(
        shortcode_atts(
            [
                'redirect' => ''
            ],
            $atts
        )
    );

    if (is_user_logged_in()) {
        return do_shortcode('[user]');
    } else {
        $login  =wp_login_form(
            [
                "echo" => false
            ]
        );
        if (!is_user_logged_in()) {
            $login .= wp_register('','',false);
        }
        return $login;
    }
}