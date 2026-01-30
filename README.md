# ITS FluentCRM Email Attachment

A WordPress plugin that extends FluentCRM to support file attachments in emails via shortcodes.

## Description

This plugin intercepts FluentCRM's mailer to allow you to attach files to emails using a simple shortcode. Instead of manually configuring attachments, you can use the `[fc_attach]` shortcode directly in your email templates.

## Requirements

- WordPress 5.6 or higher
- PHP 7.4 or higher
- FluentCRM plugin installed and activated

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure FluentCRM (Free or Pro version) is installed and activated.

## Usage

### Basic Shortcode

Add the shortcode to your FluentCRM email templates:

```php

[[FC_ATTACH::myfilename.pdf]]

```

### File location

Store files in `/wp-content/uploads/`, these are attached to the email during the send process. Subfolders within `/uploads` can be used but the subfolder must be included in the shortcode as follows:

```php

[[FC_ATTACH::mydir/myfilename.pdf]]

```

### Multiple attachments

Multiple attachments can be added as follows:

```php

[[FC_ATTACH::myfilename1.pdf]]
[[FC_ATTACH::myfilename2.pdf]]
[[FC_ATTACH::myfilename3.pdf]]

```

### Alternate method

1. Create a FluentCRM Custom Field, e.g. `email_attachments` as a multi-line field.
2. Enter the shortcode(s) with file name in the Custom Field of your FluentCRM Contact.
3. Add FluentCRM shortcode to the email template Email Body e.g. `{{contact.custom.email_attachments}}`.

**Result**

- Different files can be added per Contact using the same email template.
- Works with Campaigns and Automations with embedded emails and Sequences.

## Outcome

- Emails are sent using `wp_mail` including the files attached as standard document attachments.
- If using FluentSMTP emails are logged and include the attachment(s).
