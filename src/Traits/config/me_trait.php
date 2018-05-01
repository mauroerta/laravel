<?php

return [
    /**
     * CONFIGURATIONS FOR THE LINKABLE TRAIT
     */
    'linkable' => [
        'rest_base_url' => 'api'
    ],

    /**
     * CONFIGURATIONS FOR THE DRAFTABLE TRAIT
     */
    'draftable' => [
        'user_model' => 'App\User',
        'drafted_at_column' => 'drafted_at',
        'drafted_by_column' => 'drafted_by',
        'tables' => ['users', 'draws']
    ],

    /**
     * CONFIGURATIONS FOR THE SLUGABLE TRAIT
     */
    'slugable' => [
        'tables' => [
            'users' => ['column' => 'username']
            'articles' => ['column' => 'slug']
        ]
    ]
];
