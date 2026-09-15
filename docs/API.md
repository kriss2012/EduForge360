# EduForge360 — REST API Documentation

Base Endpoint: `https://your-domain.com/wp-json/eduforge/v1/`

Authentication: Cookie Authentication with `X-WP-Nonce` header or WordPress Application Passwords for external integrations.

---

## 1. Courses API

### `GET /courses`
Retrieve paginated list of published courses.
- **Permission:** Public
- **Response:**
```json
{
  "success": true,
  "courses": [
    {
      "id": 101,
      "title": "Python Backend & Scalable Architecture",
      "excerpt": "Comprehensive backend engineering in Python covering data structures, microservices...",
      "link": "http://localhost:8080/courses/python-backend/",
      "thumbnail": "",
      "details": {
        "duration": "10 Weeks",
        "level": "Intermediate",
        "price": 0,
        "rating": 4.9
      }
    }
  ]
}
```

### `GET /courses/{id}`
Retrieve a single course with its curriculum lessons.
- **Permission:** Public

---

## 2. Student & Enrollment API

### `GET /student/profile`
Retrieve current authenticated student profile, radar skills, and career readiness.
- **Permission:** Authenticated User
- **Response:**
```json
{
  "success": true,
  "student": {
    "id": 14,
    "name": "Aarav Sharma",
    "email": "aarav.sharma@eduforge360.edu",
    "career_readiness": 81,
    "skills": {
      "programming": { "score": 82, "level": "Advanced" },
      "database": { "score": 61, "level": "Intermediate" }
    }
  }
}
```

### `POST /enrollment`
Enroll student into a course.
- **Permission:** Authenticated User
- **Payload:**
```json
{
  "course_id": 101
}
```

---

## 3. Quizzes & Evaluation API

### `POST /quiz/submit`
Submit student answers for timed quiz evaluation.
- **Permission:** Authenticated User
- **Payload:**
```json
{
  "quiz_id": 204,
  "answers": {
    "1": ["B"],
    "2": ["T"],
    "3": ["A", "C"]
  }
}
```
- **Response:**
```json
{
  "success": true,
  "result": {
    "score": 100.0,
    "status": "passed",
    "correct_count": 3,
    "total_questions": 3,
    "attempt_number": 1
  }
}
```

---

## 4. Certificates & Verification API

### `GET /certificates/verify/{hash}`
Public verification of an issued credential.
- **Permission:** Public
- **Response:**
```json
{
  "verified": true,
  "certificate_no": "EDU-2026-00125",
  "student_name": "Aarav Sharma",
  "course": "Python Backend & Scalable Architecture",
  "score": 94.5,
  "issued_at": "2026-09-01 15:30:00"
}
```

---

## 5. Placement & Job Applications API

### `POST /job/apply`
Submit campus placement application.
- **Permission:** Authenticated User
- **Payload:**
```json
{
  "job_id": 305,
  "cover_note": "Experienced in building REST APIs with Python and WordPress."
}
```

---

## 6. Institutional Analytics API

### `GET /analytics/summary`
Retrieve institutional KPIs and trend figures for admin reports.
- **Permission:** Administrator / `view_institutional_analytics`
