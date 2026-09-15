# EduForge360 — System Architecture Document

## 1. Architectural Vision & Decoupled Design Rationale

EduForge360 is engineered under an enterprise WordPress paradigm where **Business Logic is strictly decoupled from the Presentation Layer**. 

### Why Decoupling Matters:
In beginner WordPress development, business logic (database calls, shortcodes, post types) is often placed inside `functions.php`. If the site administrator or institution switches themes, all platform data, course enrollments, quiz evaluations, and certificate verification records would become inaccessible.

In **EduForge360**:
- `wp-content/plugins/edu-forge-core`: Encapsulates all custom database tables, role definitions, REST API routes, AJAX handlers, LMS engine, quiz grading logic, and cryptographic certificate issuance.
- `wp-content/themes/edu-forge-theme`: Acts as a pure presentation layer delivering an accessible, mobile-first, and high-performance SaaS user experience.

---

## 2. High-Level System Architecture Flow

```mermaid
graph TD
    User([Student / Faculty / Recruiter]) -->|HTTPS Requests| Browser([Modern Web Browser / Mobile])
    Browser -->|Static Assets / CDN| WebServer([Nginx / Apache Web Server])
    WebServer -->|PHP 8.2 FastCGI| WordPressCore([WordPress 6.5+ Core])
    
    subgraph Presentation_Layer [Presentation Layer]
        WordPressCore --> CustomTheme([edu-forge-theme])
        CustomTheme --> Templates([front-page / player / dashboard / catalogs])
    end

    subgraph Business_Logic_Engine [Business Logic Engine]
        WordPressCore --> CorePlugin([edu-forge-core])
        CorePlugin --> CourseMgr[Class Course Manager]
        CorePlugin --> QuizEngine[Class Quiz Engine]
        CorePlugin --> CertLedger[Class Certificate Ledger]
        CorePlugin --> CareerReadiness[Class Career & Placement Manager]
        CorePlugin --> SkillMatrix[Class Skill Diagnostics Engine]
        CorePlugin --> CronJobs[WP-Cron Scheduled Tasks]
    end

    subgraph API_and_Network [API & Data Ingestion]
        Browser -->|Async AJAX| AJAXHandler[class-ajax-handler.php]
        Browser -->|JSON REST Calls| RESTEndpoints[/wp-json/eduforge/v1/...]
        AJAXHandler --> CorePlugin
        RESTEndpoints --> CorePlugin
    end

    subgraph Data_Storage_Layer [Storage & Persistence]
        CorePlugin -->|Prepared $wpdb Queries| MySQL[(MySQL 8.0 Custom Tables)]
        WordPressCore -->|Transient Caching| RedisCache[(Redis In-Memory Cache)]
        CorePlugin -->|Secure Uploads / Scoured Logs| FileStorage[(Uploads & eduforge-logs)]
    end

    subgraph ECommerce_and_Payments [Commerce Integration]
        CorePlugin -->|Auto-Enrollment Hooks| WooCommerce([WooCommerce Engine])
        WooCommerce -->|Webhook Signatures| PaymentGateways([Razorpay / Cashfree / Stripe])
    end
```

---

## 3. Core Modules & Responsibilities

| Component | Responsibility | Isolation Level |
|---|---|---|
| `EduForge_Roles` | Defines `student`, `instructor`, and `placement_officer` roles and capabilities. | Plugin |
| `EduForge_Database` | Manages 6 dedicated relational tables using `dbDelta()`. | Plugin |
| `EduForge_Course_Manager` | Calculates real course progress %, enrollment status, and syllabus hierarchy. | Plugin |
| `EduForge_Quiz_Manager` | Evaluates timed quizzes, calculates percentage, logs attempt history. | Plugin |
| `EduForge_Assignment_Manager` | Validates secure file uploads (MIME checking, anti-PHP) and faculty grading. | Plugin |
| `EduForge_Certificate_Manager` | Issues unique verifiable serials (`EDU-2026-XXXXX`), QR code URLs, and public verification endpoints. | Plugin |
| `EduForge_Skill_Manager` | Runs 9-domain skill diagnostics and executes rule-based course recommendations. | Plugin |
| `EduForge_Career_Manager` | Calculates weighted career readiness score and manages student resume state. | Plugin |
| `EduForge_Placement_Manager` | Orchestrates campus placement drives, company profiles, and application pipelines. | Plugin |
| `EduForge_WooCommerce` | Unlocks course access automatically upon `woocommerce_order_status_completed`. | Plugin |
| `EduForge_REST_API` | Exposes 10 authenticated, permission-checked REST endpoints. | Plugin |
| `EduForge_AJAX_Handler` | Handles nonced asynchronous requests without full page refreshes. | Plugin |
| `EduForge_Logger` | Writes scrubbed application logs into `wp-content/uploads/eduforge-logs/`. | Plugin |
| `EduForge_Cron` | WP-Cron schedules for deadline reminders and log retention rotations. | Plugin |
