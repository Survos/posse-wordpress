<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_user_calendar($atts)
{
    extract(
        shortcode_atts(
            [
            ],
            $atts
        )
    );


    ob_start();

    /** @var \Posse\SurveyBundle\Services\ProjectManager $pm */
    $pm = Posse::getProjectManager();
    $projects = $pm->getAllActiveProjects();
    ?>

    <div class="user-calendar"></div>

    <?php
    $return = ob_get_clean();

    return $return;
    return $return;
}