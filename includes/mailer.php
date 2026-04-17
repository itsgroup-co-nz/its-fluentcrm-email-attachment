<?php

// 3. Mailer Override
namespace ITSMailerOverride;

use FluentCrm\Framework\Support\Arr;

/**
 * Custom mailer class for FluentCRM that adds attachment support
 * via custom [[FC_ATTACH::filename]] tags in email body
 */
class Mailer
{
    /**
     * Send an email with optional attachments
     *
     * @param array $data Email data containing 'to', 'subject', 'body', 'headers'
     * @param object|null $subscriber Subscriber object for unsubscribe functionality
     * @return bool Whether the email was sent successfully
     */
    public static function send($data, $subscriber = null)
    {
        $attachments = [];

        // Parse attachment tags from email body
        if (isset($data['body'])) {
            // Find all [[FC_ATTACH::filename]] tags in the email body
            preg_match_all('/\[\[FC_ATTACH::([^\]]+)\]\]/', $data['body'], $matches);

            if (!empty($matches[1])) {
                // Define the base directory - restrict attachments to WordPress uploads folder for security
                $baseDir = wp_normalize_path(WP_CONTENT_DIR . '/uploads');

                // Process each attachment filename found
                foreach ($matches[1] as $filename) {
                    $filename = trim($filename);

                    // Build the full path by prepending the uploads directory
                    $fullPath = $baseDir . '/' . $filename;

                    // Resolve the path to handle any symlinks or relative paths
                    $resolved = realpath($fullPath);

                    if ($resolved) {
                        $resolvedNormalized = wp_normalize_path($resolved);

                        // Security check: ensure the resolved path is within the allowed base directory
                        // This prevents directory traversal attacks
                        if (strpos($resolvedNormalized, $baseDir . '/') === 0 || $resolvedNormalized === $baseDir) {
                            // Verify the file actually exists before adding to attachments
                            if (file_exists($resolvedNormalized)) {
                                $attachments[] = $resolvedNormalized;
                            } else {
                                error_log("[ITSMailerOverride] Attachment not found or unreadable: $filename");
                            }
                        } else {
                            // Log attempted access outside allowed directory
                            error_log("[ITSMailerOverride] Attachment path outside allowed directory: $filename");
                        }
                    } else {
                        // Log if realpath() failed (file doesn't exist or not accessible)
                        error_log("[ITSMailerOverride] Attachment not found or unreadable: $filename");
                    }
                }

                // Clean up: remove all attachment markers from the email body
                $data['body'] = str_replace($matches[0], '', $data['body']);
            }
        }

        // Build email headers including From, Reply-To, and List-Unsubscribe
        $headers = self::buildHeaders($data, $subscriber);

        // Extract recipient email address
        $to = $data['to']['email'] ?? '';
        if (!$to) return false;

        // Optionally include recipient name in "Name <email>" format
        if (self::willIncludeName() && !empty($data['to']['name'])) {
            $to = $data['to']['name'] . ' <' . $to . '>';
        }

        // Send the email using WordPress wp_mail function
        return wp_mail(
            $to,
            $data['subject'],
            $data['body'],
            $headers,
            $attachments
        );
    }

    /**
     * Build email headers array
     *
     * @param array $data Email data
     * @param object|null $subscriber Subscriber object for unsubscribe header
     * @return array Headers array for wp_mail
     */
    protected static function buildHeaders($data, $subscriber = null)
    {
        // Set content type to HTML with UTF-8 encoding
        $headers[] = "Content-Type: text/html; charset=UTF-8";

        // Extract From and Reply-To from data if present
        $from = Arr::get($data, 'headers.From');
        $replyTo = Arr::get($data, 'headers.Reply-To');

        if ($from) {
            $headers[] = "From: {$from}";
        }

        if ($replyTo) {
            $headers[] = "Reply-To: {$replyTo}";
        }

        // Add List-Unsubscribe headers for compliance (RFC 2369 and RFC 8058)
        if ($subscriber && apply_filters('fluent_crm/enable_unsub_header', true, $data, $subscriber)) {
            // Generate secure unsubscribe URL
            $unsubscribeUrl = add_query_arg([
                'fluentcrm'   => 1,
                'route'       => 'unsubscribe',
                'secure_hash' => fluentCrmGetContactManagedHash($subscriber->id)
            ], site_url('index.php'));

            // Standard unsubscribe header
            $headers[] = "List-Unsubscribe: <{$unsubscribeUrl}>";

            // One-click unsubscribe header (Gmail, Yahoo requirement)
            $headers[] = "List-Unsubscribe-Post: List-Unsubscribe=One-Click";
        }

        // Allow other plugins/themes to modify headers
        return apply_filters('fluent_crm/email_headers', $headers, $data, $subscriber);
    }

    /**
     * Check if recipient name should be included in To header
     * Caches the result to avoid repeated filter calls
     *
     * @return bool Whether to include recipient name
     */
    private static function willIncludeName()
    {
        static $status = null;

        // Return cached value if already determined
        if ($status !== null) {
            return $status;
        }

        // Get and cache the filtered value
        $status = apply_filters('fluent_crm/enable_mailer_to_name', true);
        return $status;
    }
}
