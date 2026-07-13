# Premium Avto — AI Context

> **Version:** 3.1
>
> **Status:** Active
>
> **Project Phase:** Modern Product Development
>
> **Purpose:** This document is the primary entry point for every AI assistant, developer and contributor working on the Premium Avto project.
>
> **Read this document before performing any task.**

---

# 1. Project Mission

Premium Avto is a long-term software modernization project.

The reverse engineering stage has been successfully completed.

The legacy platform is no longer the subject of research.

It is now the technological foundation for building a modern product.

The project's mission is:

> **Create a modern, secure, fast and maintainable automotive company website while preserving all proven business logic accumulated by the existing system.**

The goal is not to rewrite history.

The goal is to build the future on top of a stable foundation.

---

# 2. Current Project Status

The project has successfully completed the research phase.

Completed:

- ✅ Reverse Engineering
- ✅ Legacy Architecture Analysis
- ✅ Full Core Inventory
- ✅ Administrative Module Inventory
- ✅ PHP 8 Compatibility Audit
- ✅ Local Development Environment
- ✅ Technical Documentation
- ✅ Initial Legacy Cleanup

The project has now entered a new phase:

> **Modern Product Development**

Future work is primarily focused on improving the product itself rather than continuing legacy investigation.

Phase 2 has officially started.

The project is no longer focused on legacy modernization.

Current priority is gradual frontend modernization.

Completed:

- Design System foundation created.
- DS-001 Hero Specification approved.
- Hero reference images approved.
- First modern Hero implemented.
- Approved Hero background integrated into frontend.
- H-004 — Brand Strip & Hero Responsive Alignment
- H-005 — Trust Block
- H-006 — Services Section
- H-007 — Gallery Modernization
- H-008 — Customer Benefits Section
- H-009 — Appointment Section

Current task:

- H-010 — Responsive Layout Audit


- AI documentation naming convention standardized.
- Core AI documentation now uses globally unique filenames.


Current frontend status:

- Hero, Brand Strip and Trust Block form the approved first-screen composition.
- The obsolete legacy “Why us?” block is no longer displayed on the public homepage.
- The Services Section remains managed through the existing block2 CMS module.
- Service titles, descriptions and prices continue to be loaded from the database.
- Gallery Modernization completed.
- Gallery now serves as a visual trust component.
- Existing block3 CMS module and gallery database remain unchanged.
- Gallery presentation has been modernized without changing PHP business logic.
- Phase 2 continues component by component without unnecessary changes to PHP business logic.
- Customer Benefits Section completed.
- Customer Benefits continues the product communication sequence by explaining the practical benefits received by the client.
- Customer Benefits is implemented as a static presentation component without changing the existing CMS architecture.
- Appointment Section completes the product story of the homepage.
- Desktop users are offered a service appointment form.
- On mobile, the primary CTA is a direct phone call.
- MAX is prepared as a future communication channel and currently appears as a disabled placeholder.
- Email is required in the appointment form.
- The project's contact and mail address is `premiumc@bk.ru`.
- Existing `block4` continues to provide contact information.
- The AJAX handler returns controlled JSON responses.
- Mailpit is used only for safe local mail testing.
- Real mail delivery must be verified separately on the hosting environment.
- Phase 2 continues without unnecessary changes to PHP business logic.

---

# 3. Product Vision

Premium Avto should gradually evolve into a modern web application while preserving the stability of the existing platform.

The finished product should be:

- modern;
- visually attractive;
- responsive;
- mobile-first;
- secure;
- fast;
- SEO-friendly;
- easy to maintain;
- convenient for administrators;
- pleasant for customers.

Every improvement should move the project closer to this vision.

---

# 4. Core Development Philosophy

The legacy system has already proven its value.

Its business logic should be respected.

Future development follows one principle:

> **Preserve behaviour. Improve implementation. Evolve the product.**

The project does not pursue modernization for its own sake.

Technology serves the product.

Architecture serves maintainability.

Code serves business.

---

# 5. Long-Term Goals

The long-term objectives of the project are:

- modern user interface;
- improved user experience;
- improved administrator experience;
- increased security;
- modern PHP compatibility;
- gradual frontend modernization;
- gradual backend modernization;
- reduced technical debt;
- simplified maintenance;
- long-term sustainability.

The project is evolutionary rather than revolutionary.

---

# 6. Development Priorities

When choosing between several possible improvements, priorities are:

1. Product quality
2. User experience
3. Business logic preservation
4. Security
5. Performance
6. Maintainability
7. Code readability
8. Internal architecture

Architecture is important, but only when it improves the product.

---

# 7. Golden Rules

Every contributor must follow these rules.

## Preserve proven behaviour.

Existing functionality should never change accidentally.

---

## Improve incrementally.

Small safe improvements are preferred over large rewrites.

---

## Every commit must leave the project deployable.

The project should always remain in a working state.

---

## Documentation reflects architecture.

Important architectural decisions must always be documented.

Development workflow changes should be documented in the appropriate workflow documents.

---

## Product goals drive technical decisions.

Technology is a tool.

The product is the objective.

---

# 8. AI Responsibilities

The project intentionally uses different AI systems for different responsibilities.

## ChatGPT

Primary responsibilities:

- software architecture;
- technical leadership;
- product strategy;
- modernization planning;
- documentation;
- code review;
- design decisions;
- risk analysis.

ChatGPT acts as the project's technical architect.

---

## Codex

Primary responsibilities:

- implementation;
- repetitive refactoring;
- editing multiple files;
- mechanical modernization;
- code generation;
- routine development tasks.

Codex acts as the implementation engineer.

---

# 9. Standard Development Workflow

Every significant task follows the same workflow.

```text
Idea

↓

Discussion

↓

Architecture

↓

Documentation

↓

Implementation

↓

Testing

↓

Commit

↓

Release
```

No stage should be skipped.

---

# 10. Documentation Policy

Project documentation has reached a mature state.

Future documentation updates should primarily accompany:

- architectural decisions;
- major product features;
- infrastructure changes;
- important modernization milestones;
- significant development workflow improvements.

Core project documents intended for AI collaboration should use globally unique filenames.

Minor implementation details should not require constant documentation rewrites.

The primary development focus is now the product itself.

---
# 11. Modernization Strategy

Modernization follows an evolutionary model.

Priority order:

1. Preserve business logic.
2. Improve usability.
3. Improve maintainability.
4. Improve security.
5. Improve performance.
6. Simplify architecture where beneficial.
7. Remove technical debt gradually.

Large rewrites are intentionally avoided unless they provide substantial long-term value.

---

# 12. Technical Debt Strategy

Technical debt should be removed gradually.

Preferred order:

- security improvements;
- obsolete PHP features;
- frontend modernization;
- code cleanup;
- architectural simplification.

Every improvement should be independently testable and easily reversible.

---

# 13. Git Philosophy

Git is part of the development process.

Recommended principles:

- one logical task = one commit;
- descriptive commit messages;
- small reversible changes;
- stable repository after every commit.

Rollback should always remain possible.

---

# 14. Decision-Making Principles

When several solutions exist, prefer the one that:

- preserves behaviour;
- improves the product;
- minimizes risk;
- simplifies future maintenance;
- keeps implementation understandable.

Never introduce complexity without measurable benefit.

---

# 15. Definition of Success

The project is considered successful when:

- all business logic has been preserved;
- the codebase is understandable;
- PHP compatibility is modern;
- technical debt is significantly reduced;
- the interface feels contemporary;
- the administrator panel remains reliable;
- new functionality can be added safely;
- future developers can understand the project quickly.

---

# 16. Starting a New AI Session

Every new AI session should begin by understanding the project rather than rediscovering it.

Recommended startup sequence:

1. Read **AI-CONTEXT.md**.
2. Identify the current development task.
3. Read only the project documents relevant to that task.
4. Continue from the current roadmap.
5. Focus on product improvement rather than legacy investigation.

## Documentation Convention

Project documents intended for AI assistants use **globally unique filenames**.

AI should always identify project documents by **filename**, not by repository path.

Examples:

- AI-CONTEXT.md
- PROJECT-README.md
- PROJECT-PRINCIPLES.md
- PROJECT-ROADMAP.md
- DESIGN-README.md
- DS-001-HERO-SPECIFICATION.md

This convention ensures that documentation can always be identified correctly regardless of its location inside the repository or the way it is uploaded into an AI Project.

Reverse engineering should never be repeated unless explicitly requested.

---

# 17. Project Philosophy

Premium Avto is no longer a legacy research project.

It is a modern software product built on a proven technological platform.

The legacy system represents accumulated business knowledge.

Modern development should enhance that knowledge rather than replace it.

Every improvement should make the product:

- better;
- simpler;
- safer;
- faster;
- easier to maintain.

---

# 18. Project Motto

> **A modern product built on a proven foundation.**

or, in expanded form:

> **Preserve the experience.  
> Modernize the technology.  
> Build the future.**

---

# 19. Final Principle

The reverse engineering stage is officially complete.

Future development is focused on creating an excellent product rather than studying the legacy system.

Every technical decision should answer one question:

> **Does this make Premium Avto a better product?**

If the answer is yes, the change is worth considering.

If the answer is no, the change should be reconsidered.

---

# 20. Documentation Principle

Project documentation serves as the shared knowledge base for developers, ChatGPT and Codex.

Core project documents should use globally unique filenames to ensure they can always be identified correctly across independent AI conversations and Project contexts.

Documentation should evolve only when:

- project architecture changes;
- development strategy changes;
- workflow changes significantly;
- new long-term engineering conventions are adopted.

Documentation should remain stable between such milestones.

---

**Document Status**

This document is the highest-level architectural and strategic guideline for the Premium Avto project.

All future development should be consistent with the principles described here.
