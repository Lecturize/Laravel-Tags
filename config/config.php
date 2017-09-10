<?php
return [
    /*
     * Tags
     */
    'tags' => [
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
