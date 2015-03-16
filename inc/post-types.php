<?php
function posse_create_post_types()
{
    register_post_type(
        'survos-survey',
        [
            'labels'      => [
                'name'          => __('Surveys'),
                'singular_name' => __('Survey')
            ],
            'public'      => true,
            'has_archive' => true,
        ]
    );

    register_post_type(
        'job',
        [
            'labels'      => [
                'name'          => __('Jobs'),
                'singular_name' => __('Job')
            ],
            'public'      => true,
            'has_archive' => true,
        ]
    );

    register_post_type(
        'wave',
        [
            'labels'      => [
                'name'          => __('Waves'),
                'singular_name' => __('Wave')
            ],
            'public'      => true,
            'has_archive' => true,
        ]
    );

    register_post_type(
        'task',
        [
            'labels'      => [
                'name'          => __('Tasks'),
                'singular_name' => __('Task')
            ],
            'public'      => true,
            'has_archive' => true,
        ]
    );
}