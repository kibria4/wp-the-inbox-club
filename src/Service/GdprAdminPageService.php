<?php

namespace Boogiewoogie\Theme\Service;

use Boogiewoogie\Core\Service\GdprAnonymiserService;
use Boogiewoogie\Core\Service\AuditLoggerService;

/**
 * Adds "GDPR Tools" under Tools menu.
 *
 * - Form to trigger anonymisation for a user.
 * - Table of recent audit log entries.
 */
class GdprAdminPageService
{
    private GdprAnonymiserService $gdpr;
    private AuditLoggerService $logger;

    public function __construct(
        GdprAnonymiserService $gdpr,
        AuditLoggerService $logger
    ) {
        $this->gdpr   = $gdpr;
        $this->logger = $logger;
    }

    /**
     * Wire WordPress hooks.
     */
    public function register(): void
    {
        add_action('admin_menu', [$this, 'registerMenu']);
        add_action('admin_post_boogiewoogie_gdpr_anonymise_user', [$this, 'handleForm']);
    }

    public function registerMenu(): void
    {
        add_submenu_page(
            'tools.php',
            __('Boogiewoogie GDPR Tools', 'boogiewoogie'),
            __('GDPR Tools', 'boogiewoogie'),
            'manage_options',
            'boogiewoogie-gdpr-tools',
            [$this, 'renderPage']
        );
    }

    public function renderPage(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'boogiewoogie'));
        }

        $status  = isset($_GET['bw_gdpr_status']) ? sanitize_text_field($_GET['bw_gdpr_status']) : '';
        $message = isset($_GET['bw_gdpr_message']) ? sanitize_text_field(wp_unslash($_GET['bw_gdpr_message'])) : '';

        $recentLogs = $this->logger->getRecent(20);
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Boogiewoogie GDPR Tools', 'boogiewoogie'); ?></h1>

            <?php if ($status): ?>
                <div class="notice notice-<?php echo $status === 'success' ? 'success' : 'error'; ?> is-dismissible">
                    <p><?php echo esc_html($message); ?></p>
                </div>
            <?php endif; ?>

            <h2><?php esc_html_e('Anonymise User', 'boogiewoogie'); ?></h2>

            <p><?php esc_html_e('Use this form to run a GDPR anonymisation for a specific user. Dry-run is enabled by default.', 'boogiewoogie'); ?></p>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('boogiewoogie_gdpr_anonymise_user', 'boogiewoogie_gdpr_nonce'); ?>
                <input type="hidden" name="action" value="boogiewoogie_gdpr_anonymise_user" />

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label for="bw_user_id"><?php esc_html_e('User ID', 'boogiewoogie'); ?></label>
                        </th>
                        <td>
                            <input name="user_id" type="number" id="bw_user_id" class="regular-text" />
                            <p class="description">
                                <?php esc_html_e('Optional. If left empty, we will try to resolve from email.', 'boogiewoogie'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="bw_user_email"><?php esc_html_e('User Email (alternative)', 'boogiewoogie'); ?></label>
                        </th>
                        <td>
                            <input name="user_email" type="email" id="bw_user_email" class="regular-text" />
                            <p class="description">
                                <?php esc_html_e('If both ID and email are provided, ID takes precedence.', 'boogiewoogie'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="bw_reason"><?php esc_html_e('Reason', 'boogiewoogie'); ?></label>
                        </th>
                        <td>
                            <input name="reason" type="text" id="bw_reason" class="regular-text" />
                            <p class="description">
                                <?php esc_html_e('e.g. "Right to be forgotten request".', 'boogiewoogie'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Dry run only', 'boogiewoogie'); ?>
                        </th>
                        <td>
                            <label>
                                <input name="dry_run" type="checkbox" value="1" checked="checked" />
                                <?php esc_html_e('Do not actually delete/change data, just log what would happen (once implemented).', 'boogiewoogie'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Run anonymisation', 'boogiewoogie')); ?>
            </form>

            <hr />

            <h2><?php esc_html_e('Recent Audit Log Entries', 'boogiewoogie'); ?></h2>

            <?php if (empty($recentLogs)): ?>
                <p><?php esc_html_e('No audit entries found yet.', 'boogiewoogie'); ?></p>
            <?php else: ?>
                <table class="widefat striped">
                    <thead>
                    <tr>
                        <th><?php esc_html_e('Time', 'boogiewoogie'); ?></th>
                        <th><?php esc_html_e('Event', 'boogiewoogie'); ?></th>
                        <th><?php esc_html_e('Message', 'boogiewoogie'); ?></th>
                        <th><?php esc_html_e('User', 'boogiewoogie'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentLogs as $entry): ?>
                        <tr>
                            <td>
                                <?php
                                $ts = isset($entry['timestamp']) ? (int) $entry['timestamp'] : time();
                                echo esc_html(
                                    date_i18n(
                                        get_option('date_format') . ' ' . get_option('time_format'),
                                        $ts
                                    )
                                );
                                ?>
                            </td>
                            <td><?php echo esc_html($entry['event'] ?? ''); ?></td>
                            <td><?php echo esc_html($entry['message'] ?? ''); ?></td>
                            <td>
                                <?php
                                $uid = $entry['user_id'] ?? 0;
                                if ($uid && function_exists('get_userdata')) {
                                    $u = get_userdata($uid);
                                    echo $u ? esc_html($u->user_email) : esc_html((string) $uid);
                                } else {
                                    esc_html_e('N/A', 'boogiewoogie');
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <?php
    }

    public function handleForm(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to perform this action.', 'boogiewoogie'));
        }

        if (
            !isset($_POST['boogiewoogie_gdpr_nonce'])
            || !wp_verify_nonce(
                $_POST['boogiewoogie_gdpr_nonce'],
                'boogiewoogie_gdpr_anonymise_user'
            )
        ) {
            wp_die(__('Nonce verification failed', 'boogiewoogie'));
        }

        $userId    = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;
        $userEmail = isset($_POST['user_email']) ? sanitize_email(wp_unslash($_POST['user_email'])) : '';
        $reason    = isset($_POST['reason']) ? sanitize_text_field(wp_unslash($_POST['reason'])) : '';
        $dryRun    = !empty($_POST['dry_run']);

        if (!$userId && $userEmail) {
            $user = get_user_by('email', $userEmail);
            if ($user) {
                $userId = (int) $user->ID;
            }
        }

        if (!$userId) {
            $this->redirectWithStatus(
                'error',
                __('User not found. Please provide a valid user ID or email.', 'boogiewoogie')
            );
        }

        $currentAdminId = function_exists('get_current_user_id') ? get_current_user_id() : null;

        $this->gdpr->anonymiseUser($userId, [
            'reason'               => $reason ?: 'manual_admin_request',
            'dry_run'              => $dryRun,
            'source'               => 'admin_ui',
            'triggered_by_user_id' => $currentAdminId,
        ]);

        $this->logger->log(
            'gdpr_anonymise_user',
            sprintf(
                'Anonymisation %s via admin UI for user ID %d (reason: %s)',
                $dryRun ? 'dry-run' : 'executed',
                $userId,
                $reason ?: 'N/A'
            ),
            [
                'target_user_id'       => $userId,
                'dry_run'              => $dryRun,
                'reason'               => $reason,
                'triggered_by_user_id' => $currentAdminId,
            ]
        );

        $this->redirectWithStatus(
            'success',
            $dryRun
                ? __('GDPR anonymisation dry-run request queued / executed. Check logs for details.', 'boogiewoogie')
                : __('GDPR anonymisation executed successfully.', 'boogiewoogie')
        );
    }

    private function redirectWithStatus(string $status, string $message): void
    {
        $url = add_query_arg(
            [
                'page'            => 'boogiewoogie-gdpr-tools',
                'bw_gdpr_status'  => $status,
                'bw_gdpr_message' => rawurlencode($message),
            ],
            admin_url('tools.php')
        );

        wp_safe_redirect($url);
        exit;
    }
}