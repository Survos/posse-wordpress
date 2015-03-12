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
