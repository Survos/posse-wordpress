<?php


/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_job($atts)
{
    extract(
        shortcode_atts(
            [
                'short_code' => ''
            ],
            $atts
        )
    );
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if (!$project) {
        echo "!Project not found!";
    }

    if (!$short_code) {
        echo "!no code given!";
    }

    ob_start();

    /** @var \Posse\SurveyBundle\Model\Job\Job $job */
    $job = Posse::getJob($short_code);
    ?>

    <h3><?php echo $job->getTitle() ?></h3>
    <small><?php echo $job->getShortCode() ?></small>
    <p><?php echo ($job->getSurvey() && $job->getSurvey()->getDescription()) ? $job->getSurvey()->getDescription() : 'No description' ?></p>
    <?php
    $return = ob_get_clean();

    return $return;
}
