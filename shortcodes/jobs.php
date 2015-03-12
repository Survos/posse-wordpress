<?php

/**
 * return  project jobs
 * @param $atts
 * @return string
 */
function posse_jobs($atts)
{
    extract(
        shortcode_atts(
            [
                'before_list' => '<ul>',
                'after_list'  => '</ul>',
                'before_item' => '<li>',
                'after_item'  => '</li>',
                'template'    => 'code' // template => code | full
            ],
            $atts
        )
    );
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if (!$project) {
        echo "!Project not found!";
    }

    ob_start();

    $jobs = $project->getJobs();
    ?>
    <?php echo $before_list ?>
    <?php /** @var \Posse\SurveyBundle\Model\Job\Job $job */
    foreach ($jobs as $job): ?>
        <?php echo $before_item ?>
        <?php if ($template == 'code'): ?>
            <?php echo $job->getShortCode() ?>
        <?php elseif ($template == 'full'): ?>
            <?php echo do_shortcode('[job short_code="'.$job->getShortCode().'"]') ?>
        <?php endif ?>
        <?php echo $after_item ?>
    <?php endforeach ?>
    <?php echo $after_list ?>


    <?php
    $return = ob_get_clean();

    return $return;
}
