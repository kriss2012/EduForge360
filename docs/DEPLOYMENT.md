# EduForge360 — Deployment & Operations Guide

## 1. Local Development Setup

### Option A: Docker Compose (Recommended)
```bash
# 1. Clone repository
git clone https://github.com/kriss2012/EduForge360.git
cd EduForge360

# 2. Configure environment
cp .env.example .env

# 3. Start containers
docker-compose up -d

# 4. Access instances:
# WordPress:   http://localhost:8080
# phpMyAdmin:  http://localhost:8081
```

### Option B: Local XAMPP Setup
1. Copy `EduForge360` directory into `C:\xampp\htdocs\eduforge360` (or symlink).
2. Create database `eduforge360_db` via phpMyAdmin (`http://localhost/phpmyadmin`).
3. Complete standard WordPress installation.
4. Activate custom theme: `Appearance -> Themes -> EduForge360 Theme`.
5. Activate custom plugin: `Plugins -> Installed Plugins -> EduForge360 Core`.
6. Run Seeder: Navigate to `EduForge360 -> Demo Seeder` and click **Seed Complete Demo Ecosystem**.

---

## 2. Production Deployment Checklist

| Step | Action | Verification |
|---|---|---|
| **1. HTTPS / SSL** | Enforce SSL with Let's Encrypt / Cloudflare. | `FORCE_SSL_ADMIN = true` in `wp-config.php` |
| **2. Debugging Off** | Disable error display in production. | `WP_DEBUG = false`, `WP_DEBUG_DISPLAY = false` |
| **3. Object Caching** | Enable Redis in-memory object cache. | `WP_CACHE = true`, Redis connected on port 6379 |
| **4. Database Indexes** | Ensure all 6 custom tables have indexes active. | Verified via `dbDelta()` on plugin activation |
| **5. Nginx Hardening** | Apply `docker/nginx.conf` security headers. | Check headers: `X-Frame-Options`, `X-Content-Type-Options` |
| **6. Uploads Lockdown** | Protect `wp-content/uploads/eduforge-logs/`. | Direct HTTP access returns 403 Forbidden |
| **7. Production SMTP** | Configure transactional SMTP gateway. | Test verification emails & welcome notifications |
| **8. System Cron** | Replace web WP-Cron with system crontab. | `DISABLE_WP_CRON = true`, trigger via `* * * * * wp-cron.php` |
