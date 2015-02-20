<?php


/**
 * simply return current project attribute
 * @param $atts
 * @return string
 */
function posse_project_attribute($atts)
{
    extract(
        shortcode_atts(
            [
                'attribute' => 'title'
            ],
            $atts
        )
    );

    ob_start();

    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();

    if ($project) {
        $method = 'get'.ucfirst($attribute);
        if (method_exists($project, $method)) {
            echo $project->$method();
        }
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
    extract(
        shortcode_atts(
            [
                'before_list' => '<ul>',
                'after_list'  => '</ul>',
                'before_item' => '<li>',
                'after_item'  => '</li>',
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
        <?php echo $job->getCode() ?>
        <?php echo $after_item ?>
    <?php endforeach ?>
    <?php echo $after_list ?>


    <?php
    $return = ob_get_clean();

    return $return;
}

/**
 * return  project forms
 * @param $atts
 * @return string
 */
function posse_surveys($atts)
{
    extract(
        shortcode_atts(
            [
                'before_list' => '<ul>',
                'after_list'  => '</ul>',
                'before_item' => '<li>',
                'after_item'  => '</li>',
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

    $forms = $project->getSurveys();
    ?>
    <?php echo $before_list ?>
    <?php /** @var \Posse\SurveyBundle\Model\Job\Job $job */
    foreach ($forms as $form): ?>
        <?php echo $before_item ?>
        <?php echo $form->getCode() ?>
        <?php echo $after_item ?>
    <?php endforeach ?>
    <?php echo $after_list ?>

    <?php
    $return = ob_get_clean();

    return $return;
}

