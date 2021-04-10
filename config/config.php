<?php

return [

    'tags' => [
        /*
         * The tag model.
         */
        'model' => \Lecturize\Tags\Models\Tag::class,

        /*
         * The tag model.
         */
        'model_pivot' => \Lecturize\Tags\Models\Taggable::class,

        /*
         * Main table
         */
        'table' => 'tags',

        /*
         * Relationship table
         */
        'table_pivot' => 'taggables',

        /*
         * Filters
         */
        'filters' => [
            '&' => '',
            '@' => '',
            '/' => '',
        ],

        /*
         * Regular expression patterns
         */
        'patterns' => [
            '/\(([^)]+)\)/' => '',
        ],

        /*
         * Camel case tags?
         */
        'camel_case' => false,
    ],

];
