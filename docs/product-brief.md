# ITS FluentCRM Email Attachment — Product Brief

## What Is It?

ITS FluentCRM Email Attachment adds file attachment support to FluentCRM emails. Out of the box, FluentCRM does not support attaching files to campaign, automation, or sequence emails. This plugin fills that gap with a two-file override that requires no changes to FluentCRM itself and no additional UI to learn.

---

## The Problem

FluentCRM is a capable email marketing and CRM platform for WordPress, but its mailer does not support attachments. For event organisers, booking systems, or any workflow that needs to send a PDF, certificate, or document alongside an automated email, this is a hard limitation. Workarounds typically involve custom code or external platforms.

---

## How It Works

Authors add a simple marker anywhere in a FluentCRM email template body:

```
[[FC_ATTACH::myfile.pdf]]
```

When FluentCRM sends the email, the plugin intercepts the send call, extracts the marker, attaches the file from the WordPress uploads folder, strips the marker from the body, and delivers a clean email with the file attached — all transparently.

For template authors who prefer standard shortcode syntax, the `[fc_attach file="myfile.pdf"]` shortcode outputs the same marker.

---

## Key Features

| Feature | Detail |
|---|---|
| Simple syntax | `[[FC_ATTACH::filename]]` inline in any FluentCRM email template |
| Multiple attachments | One marker per file — unlimited attachments per email |
| Subdirectory support | `[[FC_ATTACH::contracts/myfile.pdf]]` — paths relative to `/uploads/` |
| Per-contact attachments | Store markers in a FluentCRM Custom Field; reference via `{{contact.custom.field_name}}` — different files per contact, same template |
| FluentSMTP compatible | Attachments appear in FluentSMTP email logs |
| Contact history | Sent email copy in the contact record includes attachments |
| Works everywhere | Campaigns, automations, sequences, embedded emails |
| No admin UI needed | Configuration is inline in the template body |

---

## Per-Contact Attachments

The most powerful use case: different files for different contacts using the same email template.

1. Create a FluentCRM Custom Field (e.g. `email_attachments`, multi-line text)
2. Add `[[FC_ATTACH::filename.pdf]]` markers to each contact's Custom Field value
3. Reference the field in the email template: `{{contact.custom.email_attachments}}`

When FluentCRM processes the template for each contact, it substitutes the custom field value, then the plugin finds and attaches the file(s) specific to that contact. This works across campaigns, automations, and sequences.

---

## Technical Approach

The plugin uses a PHP `class_alias()` to substitute FluentCRM's internal Mailer class before FluentCRM loads it. This is a clean override that:

- Requires no modifications to FluentCRM's code
- Survives FluentCRM updates as long as the Mailer's class name and namespace remain stable
- Preserves all of FluentCRM's existing header behaviour (From, Reply-To, List-Unsubscribe, filters)

File path security is enforced at send time: all attachment paths are resolved and validated to confirm they stay within the WordPress uploads directory. Attempts to reference files outside that directory are rejected and logged.

---

## Who It's For

- ITS Group WordPress deployments running FluentCRM for event or membership communications
- Any workflow where automated emails need to carry PDFs, tickets, certificates, or documents
- Use cases requiring per-recipient file variation without building custom code per campaign

---

## Current Status

The plugin is production-ready. Version 1.1 is deployed and functional. There is no admin settings page — configuration is entirely inline in FluentCRM email templates.
