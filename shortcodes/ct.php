<?php

/**
 * return ct data
 * @param $atts
 * @return string
 */
function posse_ct($atts)
{
    extract(
        shortcode_atts(
            [
                'id' => ''
            ],
            $atts
        )
    );

    if (!$id) {
        echo "!no ID given!";
    }

    ob_start();

    /** @var \Posse\SurveyBundle\Model\Survey\Survey $survey */
    $ct = Posse::getCt($id);
    ?>

    <?php if ($ct): ?>
    <?php echo $ct ?>
<?php endif ?>



    <?php
    $return = ob_get_clean();

    return $return;
}