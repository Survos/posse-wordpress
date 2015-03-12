<?php


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

