<?php

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

