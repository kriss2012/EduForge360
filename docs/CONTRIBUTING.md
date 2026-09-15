# Contributing to EduForge360

We welcome contributions to EduForge360. To maintain enterprise standards, please adhere to the following workflow:

## Branching Strategy
- `main`: Production release branch.
- `develop`: Primary integration branch.
- `feature/*`: Specific modules (e.g. `feature/course-system`, `feature/quiz-engine`).

## Commit Convention
Follow conventional commits:
- `feat: add course enrollment system`
- `fix: resolve quiz submission timer edge-case`
- `perf: optimize custom table indexation`
- `security: enforce MIME inspection on file uploads`
- `docs: update REST API documentation`

## Code Standards
- Adhere to WordPress Core PHP Coding Standards.
- Run `php -l` on all modified files prior to commit.
- Never commit `.env` or sensitive credentials.
