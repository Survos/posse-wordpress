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

    return wp_login_form(
        [
            "echo" => false
        ]
    );
}