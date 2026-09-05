# Screenshot Guide

For a clean portfolio presentation, capture the same authorized test before and after installing the plugin.

## Recommended screenshots

1. **Before: REST API user enumeration**
   - Command: `curl -i https://example.com/wp-json/wp/v2/users`
   - Show that user data is returned
   - Redact the real domain and usernames before publishing

2. **Before: XML-RPC enabled**
   - Show `system.multicall` and `pingback.ping` in the XML-RPC method list
   - Redact the real domain

3. **After: REST API enumeration blocked**
   - Run the same REST request
   - Show the **403** response

4. **After: XML-RPC blocked**
   - Run `curl -i https://example.com/xmlrpc.php`
   - Show the denied response

5. **Optional: WordPress plugin active**
   - Show **Bettycoder Privacy Hardening** activated in WordPress
   - Crop out usernames, email addresses, site admin details, and unrelated plugins if desired

## Privacy checklist

Before committing screenshots:

- redact real usernames
- redact admin email addresses
- redact IP addresses
- redact hosting/account identifiers
- redact API keys, cookies, nonces, or tokens
- check browser tabs and terminal scrollback for unrelated personal information
