# Bettycoder WordPress Privacy Hardening

A lightweight WordPress security and privacy hardening plugin that reduces common information disclosure and enumeration without turning a personal blog into a giant security stack.

## What it does

- Blocks unauthenticated WordPress REST API user enumeration
- Blocks public author archive enumeration
- Removes users from the WordPress XML sitemap
- Disables XML-RPC
- Disables pingbacks and removes the `X-Pingback` header
- Removes common WordPress fingerprinting metadata
- Replaces detailed login errors with a generic message
- Adds conservative privacy/security headers

## Why I built it

This project came from manually testing my own WordPress site during PenTest+ practice.

The workflow was:

1. Enumerate the site with tools such as DirBuster, Gobuster, FFUF, and curl
2. Confirm exposed WordPress users, theme/plugin information, and XML-RPC methods
3. Document the baseline
4. Build a small hardening plugin
5. Retest the same endpoints after remediation

The goal is not security through obscurity. The goal is to reduce unnecessary attack surface and information leakage while still keeping WordPress usable.

## Installation

1. Download the repository as a ZIP, or copy the plugin file into a folder named:

   ```
   bettycoder-privacy-hardening
   ```

2. Place it under:

   ```
   wp-content/plugins/
   ```

3. In WordPress, go to **Plugins**
4. Activate **Bettycoder Privacy Hardening**

## Suggested before/after validation

Use your own authorized WordPress site.

### REST API user enumeration

```bash
curl -i https://example.com/wp-json/wp/v2/users
```

Before hardening, a site may return public user records.

After hardening, unauthenticated requests should return **403**.

### XML-RPC

```bash
curl -i https://example.com/xmlrpc.php
```

Before hardening, WordPress may return:

```
XML-RPC server accepts POST requests only.
```

After hardening, the endpoint should be denied.

### Author enumeration

```bash
curl -I "https://example.com/?author=1"
```

After hardening, public author archives should no longer reveal the account.

## Security notes

This plugin reduces attack surface and information disclosure. It is **not** a replacement for:

- keeping WordPress, plugins, and themes updated
- strong unique passwords
- MFA
- regular backups
- least privilege
- rate limiting
- a WAF or host-level controls when appropriate

Disabling XML-RPC may affect Jetpack, the WordPress mobile app, remote publishing, or other services that depend on XML-RPC.

## Screenshots

See the [screenshots guide](screenshots/README.md) for the before/after evidence I am collecting for this project.

## License

MIT
