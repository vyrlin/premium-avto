# Premium Avto — Technical Debt

> **Document:** Technical Debt
>
> **Version:** 1.0
>
> **Status:** Active
>
> **Purpose:** Track known legacy issues and define safe modernization priorities.

---

# 1. Goal

This document lists known technical debt in the Premium Avto legacy system.

The purpose is not to rewrite the project, but to improve it gradually and safely.

---

# 2. Priority Order

Technical debt must be handled in this order:

1. Security
2. PHP compatibility
3. Stability
4. Code readability
5. Frontend improvements
6. Architecture improvements

---

# 3. Known Technical Debt

## Security

- Legacy password hashing uses MD5.
- SQL queries are manually assembled.
- No prepared statements.
- Input filtering depends heavily on `secur()`.
- Admin protection should be reviewed carefully.

Risk: High  
Action: Improve gradually, without breaking login or admin behaviour.

---

## PHP Compatibility

- Legacy PHP syntax may cause warnings on modern PHP.
- `create_function()` may exist in helper code.
- Short PHP tags may exist.
- Deprecated behaviour may appear on PHP 8+.

Risk: Medium  
Action: Fix one compatibility issue per commit.

---

## Database Layer

- Custom DB wrapper is used.
- SQL logic is mixed with application logic.
- No migration system.

Risk: Medium  
Action: Document before changing.

---

## Architecture

- No namespaces.
- No Composer autoloading.
- Global state is used.
- Routing depends on `$_GET['link']` and global `$_PATH`.

Risk: High  
Action: Do not change routing without separate analysis.

---

## Frontend

- Legacy layout.
- Fixed-width design.
- Mobile adaptation may be limited.
- Old HTML/CSS structure.

Risk: Low to Medium  
Action: Improve after PHP and security issues are stable.

---

# 4. Safe First Candidates

The safest first modernization tasks are:

1. Replace `create_function()` if found.
2. Fix PHP warnings that do not affect behaviour.
3. Improve comments around legacy functions.
4. Remove unused obvious dead code only after confirmation.
5. Improve frontend styles without changing content logic.

---

# 5. Do Not Touch Without Separate Plan

These components are stable and risky:

- `PAGE`
- `DB`
- `USER`
- `Form`
- `secur()`
- `get_block()`
- `path.php`

Any change here requires:

- separate discussion;
- documentation update;
- full testing checklist.

---

# 6. Rule

Each technical debt item must be fixed with:

- one clear task;
- one commit;
- testing after change;
- documentation update if behaviour or architecture changes.

---

# Status

This document is the official technical debt register for Phase 1 — Safe Modernization.
# Compatibility

## TD-001 — Replace create_function()

Status

✅ Completed

Verification

- create_function() no longer exists in the project.
- sort_by() and order_by() now use anonymous functions (Closure).
- Behaviour preserved.

**Priority**

- Low

**Risk**

- Low

**Location**

```text
_core/_functions/funcs.php
```

**Functions**

- `sort_by()`
- `order_by()`

**Problem**

Both functions use PHP's deprecated `create_function()`, which was removed in PHP 8.

**Current status**

- Only two occurrences exist in the project.
- No usages of `sort_by()` or `order_by()` have been found during the current inventory.
- The project currently works because these functions are not executed.

**Planned solution**

Replace `create_function()` with anonymous functions (`Closure`).

**Verification**

- Search confirms zero remaining `create_function()` calls.
- Application behaviour remains unchanged.
- PHP 8 compatibility is improved.

**Notes**

This should become the first source code modernization task after the core inventory is completed.