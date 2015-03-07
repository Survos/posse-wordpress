<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_projects($atts)
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

    <ul>
        <?php foreach ($projects as $p): ?>
            <?php $link = $pm->projectRoot($p) ?>
            <li>
                <a target="_blank" href="<?php echo $link ?>"><?php echo $p->getCode() ?></a>
            </li>
        <?php endforeach ?>
    </ul>



    <?php
    $return = ob_get_clean();

    return $return;
}