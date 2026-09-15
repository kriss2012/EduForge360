# EduForge360 — Security Architecture & Hardening Guide

## 1. Security Philosophy

EduForge360 is built strictly following OWASP Top 10 recommendations and WordPress Security Standards. Security is implemented defensively across all layers.

---

## 2. Defensive Controls Implemented

### 1. SQL Injection Prevention
- **Rule:** Never concatenate raw user input into SQL queries.
- **Implementation:** Every custom database query passes through `$wpdb->prepare()` with explicit format specifiers (`%d` for integers, `%s` for strings, `%f` for decimals).
- Custom table names are constructed using `$wpdb->prefix . 'eduforge_' . $table` rather than arbitrary inputs.

### 2. Cross-Site Scripting (XSS) Prevention
- **Rule:** Sanitize early upon input; escape late upon rendering.
- Input Sanitization: `sanitize_text_field()`, `sanitize_textarea_field()`, `sanitize_email()`, `absint()`.
- Output Escaping: `esc_html()`, `esc_attr()`, `esc_url()`, and `wp_kses_post()`.

### 3. Cross-Site Request Forgery (CSRF) Mitigation
- All state-altering AJAX requests enforce cryptographic nonces:
  ```php
  check_ajax_referer('eduforge_secure_nonce', 'nonce');
  ```
- REST API mutations check `X-WP-Nonce` headers.

### 4. Privilege Escalation & Capability Checks
- Endpoints enforce granular role checks:
  - Students cannot review assignments or publish courses.
  - Faculty cannot modify system core settings or plugins.
  - Placement Officers can only view student readiness and manage corporate drives.

### 5. File Upload Hardening (Assignments & Portfolios)
- Executable files (`.php`, `.phtml`, `.exe`, `.sh`, `.bat`, `.js`) are strictly prohibited.
- Allowed formats: PDF, DOC, DOCX, PPT, PPTX, JPG, PNG, WEBP, ZIP.
- Files undergo dual verification:
  1. Extension & reported MIME validation via `wp_check_filetype()`.
  2. Binary header inspection via `finfo_file(FILEINFO_MIME_TYPE)` to detect MIME spoofing.
- Strict 20MB file size limit enforced.

### 6. Logging Privacy & Credential Scrubbing
- Application logs in `wp-content/uploads/eduforge-logs/` are protected with an Apache `.htaccess` (`Deny from all`) and Nginx location block.
- Automated regex scrubbing removes passwords, API keys, and authorization tokens prior to writing log entries.
