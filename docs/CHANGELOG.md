# EduForge360 — Platform Changelog

All notable changes to the EduForge360 platform will be documented in this file.

## [1.0.0] - 2026-09-15
### Added
- Complete custom WordPress Theme (`edu-forge-theme`) with 15 homepage sections, distraction-free Course Player, and SaaS Student Dashboard.
- Custom Core Engine Plugin (`edu-forge-core`) housing 6 custom indexed relational database tables via `dbDelta()`.
- 11 Custom Post Types (`courses`, `lessons`, `quizzes`, `assignments`, `events`, `companies`, `jobs`, `certificates`, `projects`, `testimonials`, `resources`).
- 6 Custom Taxonomies (`course_category`, `course_level`, `skill_category`, `event_category`, `job_category`, `industry`).
- 9-domain adaptive skill assessment engine with rule-based course recommendations.
- Interactive ATS Resume Builder with live layout synchronization and print-to-PDF formatting.
- Verifiable Certificate Ledger with unique serial generation (`EDU-2026-XXXXX`) and public QR verification endpoints (`/verify-certificate/{id}`).
- Campus recruitment drive board and applicant pipeline management.
- 10 authenticated custom REST API endpoints under `/wp-json/eduforge/v1/`.
- Nonce-verified asynchronous AJAX handlers for instant course filtering, lesson completion, and quiz evaluation.
- WooCommerce integration hooks for automated course enrollment on order completion.
- Multi-container Docker environment (PHP 8.2, MySQL 8.0, Redis, phpMyAdmin, WP-CLI).
- GitHub Actions CI/CD pipeline for automated linting, security checking, and container build validation.
- Industrial demo data seeder including demo student `Aarav Sharma` baseline profile.
