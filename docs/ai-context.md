# Premium Avto — AI Context

> **Version:** 2.0
>
> **Status:** Active
>
> **Purpose:** This document is the primary entry point for any AI assistant, developer, or contributor joining the Premium Avto project.
>
> **Read this document before performing any task.**
## Response Quality Rules

AI should prefer replacing an entire document over providing multiple partial edits.

When a partial edit is required:

- specify the exact section to replace;
- provide the complete replacement as a single copyable Markdown block;
- never split replacement text across multiple messages.

If the proposed format causes confusion, the workflow should be updated before continuing development.
---

## Mandatory Project Documents

Before starting any task, the AI assistant must read the following documents in this order:

1. README.md
2. ai-context.md
3. ai-development-workflow.md
4. project-principles.md
5. roadmap.md

These documents define the project architecture, development methodology, and collaboration rules.

No implementation work should begin before they have been reviewed.
These documents are considered the authoritative source for project architecture, development workflow, and modernization strategy. If any later instruction conflicts with them, the project documentation should be updated first before changing the development process.
---

# 1. Project Overview

Premium Avto is a legacy PHP website that is being modernized through **incremental evolution**, not a rewrite.

The project already has a working production version.

The objective is to improve the system while preserving all existing functionality.

The project is developed using Git with small, reversible changes.

---

# 2. Current Project Phase

Current Phase:

> **Phase 1 — Safe Modernization**

Completed:

✅ Local development environment

✅ Legacy analysis

✅ Reverse Engineering

✅ Architecture documentation

✅ Database documentation

✅ Routing documentation

✅ Core documentation

✅ Development documentation

Next milestone:

> Begin safe modernization of the legacy codebase.

---

# 3. Primary Objective

The project has one fundamental rule.

> **Preserve behaviour first. Improve implementation second.**

Correct behaviour is always more important than cleaner code.

Working software has priority over architectural perfection.

---

# 4. Development Philosophy

The project follows these principles:

- Evolution instead of revolution.
- Small safe improvements.
- Fully working project after every commit.
- Rollback must always be possible.
- Documentation evolves together with code.

Large rewrites are intentionally avoided.

---

# 5. AI Responsibilities

This project intentionally uses two AI assistants.

## ChatGPT

Primary responsibilities:

- architecture;
- planning;
- reverse engineering;
- documentation;
- code review;
- design decisions;
- modernization strategy.

ChatGPT acts as the **project architect**.

---

## Codex

Primary responsibilities:

- implementation;
- repetitive refactoring;
- editing multiple files;
- code generation;
- migration tasks;
- project-wide transformations.

Codex acts as the **implementation engineer**.

---

# 6. Collaboration Model

Development workflow:

```text
Idea

↓

Architecture discussion

↓

Documentation update

↓

Implementation

↓

Testing

↓

Commit

↓

Push
```

Neither AI should skip these steps.

---

# 7. Project Rules

Always follow these rules.

## Rule 1

Never rewrite working modules without a confirmed reason.

---

## Rule 2

One logical task = one commit.

---

## Rule 3

One architectural decision = one documentation update.

---

## Rule 4

Every commit must leave the project in a working state.

---

## Rule 5

Every modification must be reversible using Git.

---

## Rule 6

Documentation is part of the source code.

Changing architecture without updating documentation is considered an incomplete task.

---

# 8. Required Documentation

Before modifying code, consult the appropriate documentation.

## Project

- docs/README.md

---

## Architecture

- docs/architecture.md
- docs/core.md
- docs/routes.md
- docs/database.md

---

## Development

- docs/project-principles.md
- docs/roadmap.md
- docs/technical-debt.md
- docs/testing-checklist.md

---

## Historical

- docs/legacy-system.md
- docs/changes-for-start-in-laragon.md
- docs/php8-compatibility.md

---

# 9. Confirmed Architecture

The following facts have been verified.

Framework:

- custom PHP mini-framework.

Core components:

- PAGE
- DB
- USER
- Form framework
- Template engine

Routing:

- custom routing through `$_GET['link']`
- global routing state stored in `$_PATH`

Templates:

- landing.html
- cabinet.html
- print.html

Database:

```text
texts
gallery
seo
users
```

The database intentionally remains extremely small.

---

# 10. Stable Components

These components are considered stable.

Changing them requires additional analysis.

- PAGE
- DB
- USER
- Form
- secur()
- get_block()
- path.php

Improvements should preserve compatibility.

---

# 11. Technical Debt Strategy

Known technical debt is tracked in

```text
docs/technical-debt.md
```

Technical debt is removed gradually.

Priority order:

1. Security
2. PHP compatibility
3. Architecture
4. UI improvements

---

# 12. Testing Policy

Every code modification requires testing.

Minimum checklist:

- public homepage;
- admin login;
- content blocks;
- gallery;
- SEO;
- database connection;
- PHP errors.

Detailed checklist:

```text
docs/testing-checklist.md
```

---

# 13. Git Workflow

Recommended workflow:

```text
Discuss

↓

Document

↓

Implement

↓

Test

↓

Commit

↓

Push
```

Recommended commit prefixes:

```text
Add

Fix

Refactor

Document

Update

Remove
```

Every important milestone should be tagged.

---

# 14. Coding Guidelines

Prefer:

- readable code;
- small functions;
- centralized logic;
- backward compatibility.

Avoid:

- duplicated code;
- unnecessary dependencies;
- global redesign;
- hidden behavioural changes.

---

# 15. Decision-Making Rules

When multiple solutions exist:

Prefer the solution that:

1. preserves behaviour;
2. minimizes risk;
3. requires the smallest change;
4. keeps rollback simple;
5. improves maintainability.

Never choose a solution solely because it is newer.

---

# 16. Documentation Policy

Documentation has equal importance to source code.

Whenever architecture changes:

- update documentation;
- then update code;
- then test.

Documentation should always reflect the current implementation.

---

# 17. Long-Term Vision

At project completion Premium Avto should:

- run on modern PHP versions;
- preserve all business functionality;
- contain complete technical documentation;
- have minimal technical debt;
- be understandable by a new developer within one hour;
- be safely maintainable.

---

# 18. Starting a New AI Session

At the beginning of every new session:

1. Read this document.
2. Determine the current project phase.
3. Read only the documentation required for the current task.
4. Continue from the current roadmap.
5. Do not propose rewriting the project.

---

# 19. Project Motto

> **Understand first.  
> Preserve second.  
> Improve third.**

---

# 20. Final Principle

This project is not about writing new code.

It is about carefully improving an existing system while respecting its history, preserving its functionality, and making every change understandable, testable, and reversible.