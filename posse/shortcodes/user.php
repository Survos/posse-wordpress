<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_user($atts)
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );


    $return = Posse::getCurrentUserInfo();

    return $return;
}