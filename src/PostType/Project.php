<?php

namespace Boogiewoogie\Theme\PostType;

use Extended\ACF\Fields\Select;
use Boogiewoogie\Core\PostType\AbstractPostType;

class Project extends AbstractPostType
{
    protected function setProperties(): void
    {
        $this->postType = 'project';
        $this->singular = 'Project';
        $this->plural   = 'Portfolio';

        //Old args
        $this->args = [
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'],
            'show_in_rest' => true,
            'hierarchical' => false,
            'publicly_queryable' => true,
            'rewrite' => [
                'slug' => 'portfolio',
                'with_front' => false,
            ],
            'menu_icon' => 'dashicons-open-folder',
        ];

        $this->args = [
            'public' => true,
            'has_archive' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'revisions'],
            'show_in_rest' => true,
            'hierarchical' => false,
            'publicly_queryable' => true,
            'menu_icon'   => 'dashicons-portfolio',
            'rewrite' => [
                'slug' => 'portfolio',
                'with_front' => false,
            ],
        ];
    }

    protected function setFields(): void
    {
        $this->fields = [
            Select::make('Client Type', 'client_type')
                ->choices([
                    'Private Client' => 'Private Client',
                    'Development' => 'Development',
                ])
                ->default('Private Client')
                ->required(),
            // Add more fields as necessary
        ];
    }
}
