<?php

namespace Boogiewoogie\Theme\Service;

/**
 * Theme-level GDPR scrubber.
 *
 * Attaches to the boogiewoogie_gdpr_anonymise_user_entities hook
 * and performs the actual data scrubbing based on config.
 *
 * Config shape (from boogiewoogie_gdpr_config filter):
 *
 * [
 *   'post_types' => [
 *     'case_study' => [
 *       'meta_keys' => ['client_name', 'client_email'],
 *     ],
 *   ],
 *   'user_meta' => ['phone', 'address'],
 *   'comments'  => ['author_email'],
 * ]
 */
class GdprScrubberService
{
    public function registerHooks(): void
    {
        add_action(
            'boogiewoogie_gdpr_anonymise_user_entities',
            [$this, 'handleAnonymiseUser'],
            10,
            3
        );
    }

    /**
     * Perform scrubbing for a given user id.
     *
     * @param int   $userId
     * @param array $config  From boogiewoogie_gdpr_config
     * @param array $options ['dry_run' => bool, 'reason' => string, ...]
     */
    public function handleAnonymiseUser(int $userId, array $config, array $options): void
    {
        $dryRun = !empty($options['dry_run']);

        // 1) User meta
        if (!empty($config['user_meta']) && is_array($config['user_meta'])) {
            $this->scrubUserMeta($userId, $config['user_meta'], $dryRun);
        }

        // 2) Comments
        if (!empty($config['comments']) && is_array($config['comments'])) {
            $this->scrubComments($userId, $config['comments'], $dryRun);
        }

        // 3) CPT post meta (inc. ACF/meta_keys)
        if (!empty($config['post_types']) && is_array($config['post_types'])) {
            $this->scrubPostTypes($userId, $config['post_types'], $dryRun);
        }

        // Extend later for custom tables, etc.
    }

    protected function scrubUserMeta(int $userId, array $metaKeys, bool $dryRun): void
    {
        foreach ($metaKeys as $key) {
            if (!is_string($key) || $key === '') {
                continue;
            }

            if ($dryRun) {
                // In a real system you might log what would be removed.
                continue;
            }

            delete_user_meta($userId, $key);
        }
    }

    protected function scrubComments(int $userId, array $commentFields, bool $dryRun): void
    {
        $user = get_user_by('id', $userId);
        if (!$user instanceof \WP_User) {
            return;
        }

        $comments = get_comments([
            'user_id' => $userId,
            'number'  => 0,
        ]);

        foreach ($comments as $comment) {
            if (!$comment instanceof \WP_Comment) {
                continue;
            }

            if ($dryRun) {
                // Could log or collect changes here instead.
                continue;
            }

            $update = [
                'comment_ID' => $comment->comment_ID,
            ];

            // Basic example: you can make this more granular per field.
            if (in_array('author_name', $commentFields, true)) {
                $update['comment_author'] = 'Anonymous';
            }

            if (in_array('author_email', $commentFields, true)) {
                $update['comment_author_email'] = '';
            }

            if (in_array('author_url', $commentFields, true)) {
                $update['comment_author_url'] = '';
            }

            // If nothing to update, skip.
            if (count($update) > 1) {
                wp_update_comment($update);
            }
        }
    }

    /**
     * Scrub CPT posts' meta (e.g. ACF fields stored as meta keys).
     *
     * $postTypesConfig example:
     *
     * [
     *   'case_study' => [
     *     'meta_keys' => ['client_name', 'client_email'],
     *   ],
     * ]
     */
    protected function scrubPostTypes(int $userId, array $postTypesConfig, bool $dryRun): void
    {
        foreach ($postTypesConfig as $postType => $settings) {
            if (!is_string($postType) || $postType === '') {
                continue;
            }

            $metaKeys = $settings['meta_keys'] ?? [];
            if (!is_array($metaKeys) || empty($metaKeys)) {
                continue;
            }

            $postIds = get_posts([
                'post_type'      => $postType,
                'posts_per_page' => -1,
                'author'         => $userId,
                'fields'         => 'ids',
            ]);

            foreach ($postIds as $postId) {
                foreach ($metaKeys as $metaKey) {
                    if (!is_string($metaKey) || $metaKey === '') {
                        continue;
                    }

                    if ($dryRun) {
                        // Log or collect here if needed.
                        continue;
                    }

                    delete_post_meta($postId, $metaKey);
                }
            }
        }
    }
}