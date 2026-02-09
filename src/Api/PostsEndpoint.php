<?php

namespace Boogiewoogie\Theme\Api;

class PostsEndpoint
{
    public function register(): void
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(): void
    {
        register_rest_route('boogiewoogie/v1', '/posts', [
            'methods' => 'POST',
            'callback' => [$this, 'getPosts'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function getPosts(\WP_REST_Request $request): \WP_REST_Response
    {
        $postType = $request->get_param('post_type') ?: 'post';
        $taxonomy = $request->get_param('taxonomy') ?: 'category';
        $postsPerPageParam = $request->get_param('posts_per_page');
        $paged = $request->get_param('paged') ?: 1;
        $excerptWordLimit = $request->get_param('excerpt_word_limit') ?: 20;

        // If posts_per_page is empty, null, 0, or -1, set to -1 to show all posts
        $postsPerPage = (!empty($postsPerPageParam) && $postsPerPageParam > 0) ? (int)$postsPerPageParam : -1;

        $args = [
            'post_type' => $postType,
            'posts_per_page' => $postsPerPage,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => $paged,
            'no_found_rows' => false,
        ];

        $query = new \WP_Query($args);

        $posts = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $postId = get_the_ID();

                // Get taxonomy term or ACF field
                $taxonomyValue = '';
                if ($postType === 'project') {
                    $taxonomyValue = get_field('client_type', $postId) ?: '';
                } else {
                    $terms = get_the_terms($postId, $taxonomy);
                    if ($terms && !is_wp_error($terms)) {
                        $taxonomyValue = $terms[0]->name;
                    }
                }

                // Get and trim excerpt
                $excerpt = get_the_excerpt();
                if ($excerpt) {
                    $excerpt = $this->limitWords($excerpt, $excerptWordLimit);
                }

                $posts[] = [
                    'id' => $postId,
                    'title' => get_the_title(),
                    'excerpt' => $excerpt,
                    'permalink' => get_permalink(),
                    'featured_image' => get_the_post_thumbnail_url($postId, 'large'),
                    'taxonomy_value' => $taxonomyValue,
                ];
            }
            wp_reset_postdata();
        }

        return new \WP_REST_Response([
            'success' => true,
            'posts' => $posts,
            'total' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
            'current_page' => $paged,
        ], 200);
    }

    /**
     * Limit text to a specified number of words
     */
    private function limitWords(string $text, int $limit): string
    {
        // Strip HTML tags
        $text = strip_tags($text);
        
        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // If within limit, return as is
        if (count($words) <= $limit) {
            return $text;
        }
        
        // Trim to limit and add ellipsis
        $trimmed = array_slice($words, 0, $limit);
        return implode(' ', $trimmed) . '...';
    }
}