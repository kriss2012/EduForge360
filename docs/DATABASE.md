# EduForge360 — Database Architecture & Schema Specification

## 1. Relational Design Rationale

While WordPress stores generic data in `wp_posts` and `wp_postmeta`, utilizing postmeta for high-frequency relational transactions (enrollment tracking, quiz attempts, grade submissions, application pipelines) leads to massive Entity-Attribute-Value (EAV) table bloat, slow Cartesian joins, and poor query performance.

EduForge360 utilizes **6 dedicated, indexed custom tables** created via `dbDelta()` to maintain high throughput and query efficiency.

---

## 2. Custom Table Schemas

### 1. `wp_eduforge_enrollments`
Tracks student course admissions, real-time completion percentages, and milestone dates.

```sql
CREATE TABLE wp_eduforge_enrollments (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    course_id bigint(20) unsigned NOT NULL,
    progress tinyint(3) unsigned NOT NULL DEFAULT 0,
    status varchar(30) NOT NULL DEFAULT 'active',
    enrolled_at datetime NOT NULL,
    completed_at datetime DEFAULT NULL,
    PRIMARY KEY  (id),
    KEY student_id (student_id),
    KEY course_id (course_id),
    KEY status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. `wp_eduforge_quiz_attempts`
Records each evaluation trial, student score, answer payloads, and pass/fail states.

```sql
CREATE TABLE wp_eduforge_quiz_attempts (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    quiz_id bigint(20) unsigned NOT NULL,
    course_id bigint(20) unsigned NOT NULL DEFAULT 0,
    score decimal(5,2) NOT NULL DEFAULT 0.00,
    status varchar(20) NOT NULL DEFAULT 'failed',
    attempt_number int(11) NOT NULL DEFAULT 1,
    answers longtext DEFAULT NULL,
    started_at datetime NOT NULL,
    completed_at datetime DEFAULT NULL,
    PRIMARY KEY  (id),
    KEY student_id (student_id),
    KEY quiz_id (quiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3. `wp_eduforge_certificates`
Immutable academic ledger storing unique serial numbers and cryptographic SHA-256 verification hashes.

```sql
CREATE TABLE wp_eduforge_certificates (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    course_id bigint(20) unsigned NOT NULL,
    certificate_number varchar(60) NOT NULL,
    verification_hash varchar(64) NOT NULL,
    score decimal(5,2) NOT NULL DEFAULT 100.00,
    issued_at datetime NOT NULL,
    PRIMARY KEY  (id),
    UNIQUE KEY certificate_number (certificate_number),
    UNIQUE KEY verification_hash (verification_hash),
    KEY student_id (student_id),
    KEY course_id (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4. `wp_eduforge_job_applications`
Placement drive applicant pipeline tracking status transitions (`applied`, `shortlisted`, `assessment`, `interview`, `selected`, `rejected`).

```sql
CREATE TABLE wp_eduforge_job_applications (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    job_id bigint(20) unsigned NOT NULL,
    company_id bigint(20) unsigned NOT NULL DEFAULT 0,
    status varchar(30) NOT NULL DEFAULT 'applied',
    cover_note text DEFAULT NULL,
    resume_url varchar(255) DEFAULT NULL,
    applied_at datetime NOT NULL,
    updated_at datetime NOT NULL,
    PRIMARY KEY  (id),
    KEY student_id (student_id),
    KEY job_id (job_id),
    KEY status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 5. `wp_eduforge_skill_assessments`
Scores across 9 technical and aptitude domains for radar visualization and recommendation rules.

```sql
CREATE TABLE wp_eduforge_skill_assessments (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    category varchar(50) NOT NULL,
    score decimal(5,2) NOT NULL DEFAULT 0.00,
    skill_level varchar(30) NOT NULL DEFAULT 'Beginner',
    strengths text DEFAULT NULL,
    weaknesses text DEFAULT NULL,
    assessed_at datetime NOT NULL,
    PRIMARY KEY  (id),
    KEY student_id (student_id),
    KEY category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6. `wp_eduforge_assignments_submissions`
Student practical file and code submissions with faculty marks and feedback.

```sql
CREATE TABLE wp_eduforge_assignments_submissions (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    student_id bigint(20) unsigned NOT NULL,
    assignment_id bigint(20) unsigned NOT NULL,
    course_id bigint(20) unsigned NOT NULL DEFAULT 0,
    submission_text text DEFAULT NULL,
    file_url varchar(255) DEFAULT NULL,
    marks decimal(5,2) DEFAULT NULL,
    max_marks decimal(5,2) NOT NULL DEFAULT 100.00,
    feedback text DEFAULT NULL,
    status varchar(30) NOT NULL DEFAULT 'submitted',
    submitted_at datetime NOT NULL,
    reviewed_at datetime DEFAULT NULL,
    reviewed_by bigint(20) unsigned DEFAULT NULL,
    PRIMARY KEY  (id),
    KEY student_id (student_id),
    KEY assignment_id (assignment_id),
    KEY status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 3. Safe Query Execution Principles

All database interactions inside `edu-forge-core` enforce:
1. `$wpdb->prepare()` parameter binding with strict type specifiers (`%d`, `%s`, `%f`).
2. Table name abstraction using `$wpdb->prefix . 'eduforge_' . $table` to support custom WordPress database prefixes.
3. Indexes placed on primary query dimensions (`student_id`, `course_id`, `status`).
