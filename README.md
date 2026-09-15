# EduForge360 — Industrial Student Development & Learning Management Platform

> **"Build Skills. Track Growth. Shape Careers."**

[![CI/CD Pipeline](https://github.com/kriss2012/EduForge360/actions/workflows/ci.yml/badge.svg)](https://github.com/kriss2012/EduForge360/actions/workflows/ci.yml)
[![WordPress](https://img.shields.io/badge/WordPress-6.5%2B-blue.svg)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-purple.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange.svg)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED.svg)](https://docker.com)
[![WCAG](https://img.shields.io/badge/WCAG-2.1%20AA-green.svg)](https://w3.org/WAI/WCAG21/quickref/)

EduForge360 is an enterprise-grade institutional digital learning, skill assessment, career development, and placement preparation platform. Built specifically to demonstrate modern, high-standard WordPress engineering beyond visual page builders.

---

## 📑 Table of Contents
1. [Platform Vision & Core Modules](#1-platform-vision--core-modules)
2. [Architectural Separation: Theme vs Plugin](#2-architectural-separation-theme-vs-plugin)
3. [Technology Stack](#3-technology-stack)
4. [Custom Relational Database Architecture](#4-custom-relational-database-architecture)
5. [Quickstart & Docker Installation](#5-quickstart--docker-installation)
6. [Demo Accounts & Seeder](#6-demo-accounts--seeder)
7. [REST API & AJAX Architecture](#7-rest-api--ajax-architecture)
8. [Automated Credential Verification System](#8-automated-credential-verification-system)
9. [Security Hardening & Defense in Depth](#9-security-hardening--defense-in-depth)
10. [Interview Preparation & Resume Positioning](#10-interview-preparation--resume-positioning)

---

## 1. Platform Vision & Core Modules

EduForge360 coordinates the complete student lifecycle within higher education:

```
REGISTRATION ➔ PROFILE ➔ SKILL ASSESSMENT ➔ COURSE ENROLLMENT ➔
LEARNING ➔ ASSIGNMENTS & QUIZZES ➔ CERTIFICATION ➔ PLACEMENT DRIVES
```

### Key Modules:
- **Production LMS & Course Player:** Distraction-free two-column player with video embeds, code snippets, progress tracking, and adjacent lesson navigation.
- **Timed Checkpoint Quiz Engine:** Instant score evaluation, attempt history logging in custom tables, and pass/fail thresholds.
- **Assignment Review Desk:** File upload validation (PDF, ZIP, DOCX with MIME inspection) and dedicated faculty grading desk.
- **Adaptive 9-Domain Skill Diagnostics:** Evaluates algorithms, databases, DevOps, cloud, and communication, triggering rule-based course recommendations.
- **ATS Resume Builder:** Real-time form synchronization with live preview and print-to-PDF formatting.
- **Campus Placement Board:** Recruitment drive manager, applicant status transitions (`Applied`, `Shortlisted`, `Interview`, `Selected`), and company profiles.
- **Automated Certificate Ledger:** Generates unique certificates (`EDU-2026-XXXXX`) with public QR code validation (`/verify-certificate/{id}`).
- **WooCommerce Commerce Integration:** Hooks into `woocommerce_order_status_completed` to auto-enroll students into premium programs.
- **Institutional Analytics:** Real-time executive dashboards with Chart.js growth visualizations.

---

## 2. Architectural Separation: Theme vs Plugin

```mermaid
graph LR
    Client([Client Browser]) --> Theme[edu-forge-theme (Presentation Layer)]
    Client --> API[REST API & AJAX Endpoints]
    Theme --> CorePlugin[edu-forge-core (Business Logic)]
    API --> CorePlugin
    CorePlugin --> DB[(Custom Indexed MySQL Tables)]
    CorePlugin --> WC[(WooCommerce Gateway)]
```

- **`wp-content/plugins/edu-forge-core`**: Houses 100% of the platform business logic, 6 custom database tables, 11 CPTs, 6 Taxonomies, custom capabilities, REST routes, AJAX endpoints, and logger. Theme switches never destroy academic data.
- **`wp-content/themes/edu-forge-theme`**: Lightweight presentation layer adhering strictly to WordPress Template Hierarchy, featuring modern CSS variables, responsive layouts (320px to 1920px), and conditional script enqueuing.

---

## 3. Technology Stack

| Layer | Technology |
|---|---|
| **CMS Core** | WordPress 6.5+ |
| **Backend** | PHP 8.0 - 8.3 (Strict types, WP coding standards) |
| **Database** | MySQL 8.0 / MariaDB (InnoDB, Indexed relational schemas) |
| **Frontend** | Vanilla JavaScript (ES6+), Modern Semantic HTML5, Custom CSS Tokens |
| **Data Flow** | Custom REST API (`/wp-json/eduforge/v1/`) & Nonced WP AJAX |
| **E-Commerce** | WooCommerce with Indian Gateway Architecture (Razorpay/Cashfree) |
| **Containerization** | Docker, Docker Compose, Redis Caching, Nginx FastCGI |
| **CI/CD** | GitHub Actions (PHP linting, WPCS, Security audit, Docker build) |

---

## 4. Custom Relational Database Architecture

EduForge360 bypasses the slow Cartesian joins of `wp_postmeta` for high-frequency operations by deploying 6 dedicated tables created via `dbDelta()`:

1. `wp_eduforge_enrollments`: Course admission records, progress %, status, and completion dates.
2. `wp_eduforge_quiz_attempts`: Trial scores, answer payloads, attempt numbers, and pass/fail states.
3. `wp_eduforge_certificates`: Academic ledger with unique serials and SHA-256 validation hashes.
4. `wp_eduforge_job_applications`: Recruitment pipelines tracking applicant statuses.
5. `wp_eduforge_skill_assessments`: 9-domain evaluation scores, strengths, and weaknesses.
6. `wp_eduforge_assignments_submissions`: Student submissions, faculty marks, and feedback.

*See [docs/DATABASE.md](docs/DATABASE.md) for full SQL schemas and indexes.*

---

## 5. Quickstart & Docker Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/kriss2012/EduForge360.git
cd EduForge360
```

### Step 2: Configure Environment
```bash
cp .env.example .env
```

### Step 3: Run with Docker Compose
```bash
docker-compose up -d
```
Access the environment:
- **WordPress:** [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)

### Step 4: Activate Theme and Plugin
1. Go to `Appearance -> Themes` and activate **EduForge360 Theme**.
2. Go to `Plugins -> Installed Plugins` and activate **EduForge360 Core**.
3. Go to `EduForge360 -> Demo Seeder` and click **Seed Complete Demo Ecosystem**.

---

## 6. Demo Accounts & Seeder

The built-in seeder populates realistic institutional data:

| Role | Username | Password | Email | Access |
|---|---|---|---|---|
| **Super Admin** | `admin` | *(Set on setup)* | `admin@eduforge360.edu` | Full platform control |
| **Student** | `aarav` | `EduForge@2026` | `aarav.sharma@eduforge360.edu` | Learning dashboard, assessments, resume, jobs |
| **Faculty** | `radhakrishnan` | `EduForge@2026` | `dr.radhakrishnan@eduforge360.edu` | Course creation, assignment grading desk |
| **Placement** | `priya` | `EduForge@2026` | `priya.nair@eduforge360.edu` | Drive management, applicant pipeline |

### Pre-Seeded Student Baseline (Aarav Sharma):
- **Courses:** 4 Active/Completed
- **Certificates:** 2 Verified Credentials (`EDU-2026-XXXXX`)
- **Overall Progress:** 76%
- **Career Readiness Score:** 81%

---

## 7. REST API & AJAX Architecture

### REST Endpoints (`/wp-json/eduforge/v1/`):
- `GET /courses` & `GET /courses/{id}`: Course syllabus and pricing data.
- `GET /student/profile` & `GET /student/progress`: Student metrics and radar scores.
- `POST /enrollment`: Course registration.
- `POST /quiz/submit`: Timed quiz evaluation and attempt recording.
- `GET /certificates/verify/{hash}`: Public credential authentication.
- `POST /job/apply`: Campus placement application submission.
- `GET /analytics/summary`: Executive institutional statistics.

### AJAX Endpoints:
- `eduforge_filter_courses`: Dynamic catalog filtering without full page reloads.
- `eduforge_mark_lesson_complete`: Real-time progress updates in the Course Player.
- `eduforge_save_resume`: Instant resume synchronization and ATS layout preview.
- `eduforge_apply_job`: One-click campus drive applications.

*See [docs/API.md](docs/API.md) for request/response payloads.*

---

## 8. Automated Credential Verification System

When a student achieves 100% course completion:
1. An immutable record is committed into `wp_eduforge_certificates`.
2. A unique serial is assigned (e.g. `EDU-2026-00125`).
3. A cryptographic SHA-256 verification hash is generated.
4. An anti-tamper public verification URL is issued:
   ```
   https://your-domain.com/verify-certificate/EDU-2026-00125/
   ```
5. Includes a dynamic QR code that employers can scan to verify candidate authenticity.

---

## 9. Security Hardening & Defense in Depth

- **Prepared SQL Statements:** Enforced across all queries via `$wpdb->prepare()`.
- **CSRF Defense:** Cryptographic nonces (`check_ajax_referer`) on all AJAX/REST mutations.
- **XSS Mitigation:** Comprehensive sanitization and contextual escaping (`esc_html`, `esc_url`, `wp_kses_post`).
- **File Upload Protection:** Strict MIME type inspection (`finfo`), size limits (20MB), and executable file bans.
- **Audit Logging:** Scrubbed internal logs in `wp-content/uploads/eduforge-logs/` with `.htaccess` deny-all protection.

*See [docs/SECURITY.md](docs/SECURITY.md) for full security controls.*

---

## 10. Interview Preparation & Resume Positioning

### How to Present This on Your Resume:
> **EduForge360 — Industrial Student Development & Learning Management Platform**
> *Architected an enterprise WordPress platform combining LMS capabilities, 9-domain adaptive skill diagnostics, automated credential verification, and corporate placement pipelines. Implemented decoupled plugin/theme architecture, 6 custom indexed MySQL tables, 10 REST API endpoints, nonced AJAX workflows, and Docker CI/CD deployment.*

### Technical Interview Talking Points:
- **"Why use WordPress for an institutional LMS?"**
  *WordPress provides a robust foundation for authentication, user roles, content authoring, and WooCommerce billing out of the box. By implementing a dedicated custom plugin (`edu-forge-core`) with custom database tables, we avoid bloating `wp_posts` and preserve total separation between business logic and presentation.*
- **"How does your architecture prevent performance bottlenecks?"**
  *We bypass postmeta Cartesian joins for analytics and enrollment tracking by maintaining dedicated tables (`wp_eduforge_enrollments`, `wp_eduforge_quiz_attempts`). We also conditionally enqueue JavaScript and CSS only on templates where they are needed (e.g., Chart.js only loads on dashboard/analytics pages).*

---

## 📄 License
This project is licensed under the GPL-2.0+ License — see the [LICENSE](LICENSE) file for details.
