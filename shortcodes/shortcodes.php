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
                'template'    => 'code' // template => code | full
            ],
            $atts
        )
    );

    ob_start();

    $forms = Posse::getSurveys();
    ?>
    <?php echo $before_list ?>
    <?php /** @var \Posse\SurveyBundle\Model\Job\Job $job */
    foreach ($forms as $form): ?>
        <?php echo $before_item ?>
        <?php if ($template == 'code'): ?>
            <?php echo $form->getCode() ?>
        <?php elseif ($template == 'full'): ?>
            <?php echo do_shortcode('[survey code="'.$form->getCode().'"]') ?>
        <?php endif ?>
        <?php echo $after_item ?>
    <?php endforeach ?>
    <?php echo $after_list ?>

    <?php
    $return = ob_get_clean();

    return $return;
}

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

/**
 * return  project form
 * @param $atts
 * @return string
 */
function posse_survey($atts)
{
    extract(
        shortcode_atts(
            [
                'code' => ''
            ],
            $atts
        )
    );
    /** @var \Posse\SurveyBundle\Model\Project $project */
    $project = Posse::getProjectManager()->getProject();
    if (!$project) {
        echo "!Project not found!";
    }

    if (!$code) {
        echo "!no code given!";
    }

    ob_start();

    /** @var \Posse\SurveyBundle\Model\Survey\Survey $survey */
    $survey = Posse::getSurvey($code);
    ?>

    <?php if ($survey): ?>
    <h3><?php echo $survey->getTitle() ?></h3>
    <p><?php echo $survey->getDescription() ? $survey->getDescription() : 'No description' ?></p>
    <p><?php echo $survey->getOverview() ?></p>
    <strong>Questions:</strong>
    <table class="table table-condensed">
        <?php foreach ($survey->getQuestions() as $q): ?>
            <tr>
                <td>
                    <span class="label label-warning" title="<?php echo $q->getType()." -- ".$q->getText() ?>">
                        <?php echo $q->getCode() ?>
                        </span>
                </td>
                <td>
                    <small>
                        <?php if ($q->getCondition()): ?><i>if <?php echo $q->getCondition() ?></i><br><?php endif ?>
                        <?php echo $q->getSmsText() ?>
                    </small>
                </td>
                <td>
                    <?php if (count($q->getChoices())): ?>
                        <?php foreach ($q->getChoices() as $idx => $a): ?>

                            <small
                                title="<?php echo($idx + 1);
                                echo " - ";
                                echo isset($a['text']) ? $a['text'] : $a['value'] ?>"
                                class="label label-info">
                                <?php echo $a['text'] ?>
                            </small>

                        <?php endforeach ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
<?php endif ?>

    <?php
    $return = ob_get_clean();

    return $return;
}

