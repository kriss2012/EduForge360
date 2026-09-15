# EduForge360 — Quality Assurance & Testing Suite

## 1. Automated Verification Checks

### PHP Syntax Linting
Runs across all theme and plugin PHP files:
```powershell
Get-ChildItem -Path "wp-content" -Filter "*.php" -Recurse | ForEach-Object { & "C:\xampp\php\php.exe" -l $_.FullName }
```

### GitHub Actions CI/CD Pipeline
- PHP 8.2 Parallel Lint.
- WordPress Coding Standards (PHPCS) inspection.
- Security credential leak audit.
- Docker multi-stage container build test.

---

## 2. Manual End-to-End Test Matrix

| Workflow ID | User Role | Test Description | Expected Result | Status |
|---|---|---|---|---|
| **TC-01** | Guest | Visit homepage and navigate curriculum | 15 sections render; filter works without reloads | Passed ✓ |
| **TC-02** | Student | Register student account & log in | Redirects cleanly to SaaS dashboard | Passed ✓ |
| **TC-03** | Student | Take 9-domain skill assessment | Radar scores calculate; course recommendations display | Passed ✓ |
| **TC-04** | Student | 1-Click enroll into free course | Record created in `wp_eduforge_enrollments` | Passed ✓ |
| **TC-05** | Student | Mark lesson complete in Course Player | Sidebar progress bar and percentage update immediately | Passed ✓ |
| **TC-06** | Student | Submit checkpoint quiz | Attempt saved in `wp_eduforge_quiz_attempts`, score displayed | Passed ✓ |
| **TC-07** | Student | Complete course (100% progress) | Certificate generated in `wp_eduforge_certificates` | Passed ✓ |
| **TC-08** | Public | Visit `/verify-certificate/{code}` | Official verified credential card & QR load | Passed ✓ |
| **TC-09** | Student | Update resume in Resume Builder | Real-time preview synchronizes; print formatting clean | Passed ✓ |
| **TC-10** | Student | Apply for campus placement drive | Record logged in `wp_eduforge_job_applications` | Passed ✓ |
| **TC-11** | Faculty | Open assignment review desk | Submissions listed; quick-grade saves marks & feedback | Passed ✓ |
| **TC-12** | Admin | Open Institutional Analytics Center | KPI summary and Chart.js growth charts render | Passed ✓ |

---

## 3. Responsive Breakpoint Validation
- 320px, 375px, 390px, 414px (Mobile): Floating bottom navigation, touch-friendly buttons.
- 768px, 1024px (Tablet): Stacked split views, collapsible drawer navigation.
- 1280px, 1440px, 1920px (Desktop): High-resolution SaaS layout with fixed player sidebar.
