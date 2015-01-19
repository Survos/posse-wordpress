<?php


/**
 * simply return current project title
 * @param $atts
 * @return string
 */
function posse_project_title($atts)
{
    ob_start();

    $args = [];
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if ($project) {
        echo $project->getTitle();
    } else {
        echo "!Project not found!";
    }
    $return = ob_get_clean();

    return $return;
}

/**
 * return  project jobs
 * @param $atts
 * @return string
 */
function posse_jobs($atts)
{
    $args = [];
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if (!$project) {
        echo "!Project not found!";
    }

    ob_start();

    $jobs = $project->getJobs();
    ?>
    <ul>
        <?php /** @var \Posse\SurveyBundle\Model\Job\Job $job */
        foreach ($jobs as $job): ?>
            <li>
                <?php echo $job->getCode() ?>
            </li>
        <?php endforeach ?>
    </ul>

    <?php
    $return = ob_get_clean();

    return $return;
}

