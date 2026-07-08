# Premium Avto — Code Inventory

> **Document:** Code Inventory
>
> **Version:** 1.0
>
> **Status:** Active
>
> **Purpose:** Maintain a complete inventory of the Premium Avto source code and document the current implementation of every major component.

---

# 1. Goal

This document is a living inventory of the Premium Avto legacy codebase.

Unlike `architecture.md`, which explains how the system works, this document records what actually exists in the source code.

For every component we document:

- file location;
- purpose;
- responsibilities;
- dependencies;
- current status;
- modernization notes;
- risk level.

The document grows gradually during Phase 1.

---

# 2. Legend

| Status | Meaning |
|---------|---------|
| ✅ | Verified |
| 🔍 | Under investigation |
| ⚠️ | Requires attention |
| ❌ | Confirmed unused (dead code) |

---

# 3. Project Structure

```text
_core/
    _classes/
    _config/
    _functions/
    _parser/

_ajax/

admin/

public_html/

signin/
```

---

# 4. Core Classes

---

## PAGE

**File**

```text
_core/_classes/classes.php
```

**Status**

- ✅ Verified

**Risk level**

- 🔴 Very High

**Purpose**

Central page lifecycle and rendering class.

**Responsibilities**

- Stores page title.
- Stores generated page content.
- Selects page template.
- Loads requested page.
- Protects admin pages.
- Renders modal windows.
- Generates SEO tags.
- Replaces template placeholders.
- Outputs final HTML.

**Main methods**

```php
PAGE::init()
PAGE::setTitle()
PAGE::setSeo()
PAGE::setModal()
PAGE::addModal()
PAGE::unsetModal()
PAGE::unsetModals()
PAGE::getContent()
PAGE::getModal()
PAGE::getModals()
PAGE::html()
PAGE::redirect()
```

**Dependencies**

- global `$_PATH`
- global `$_CORE_ROOT`
- `USER::isAdmin()`
- `get_block()`
- `get_top_menu()`
- `get_left_menu()`
- `get_login_form()`

**Templates**

- landing.html
- cabinet.html
- print.html

**Confirmed behaviour**

- Redirects unauthorized users from `/admin/` to `/signin/`.
- Automatically selects the appropriate template.
- Loads landing blocks through `get_block()`.
- Replaces placeholders using `str_replace()`.
- Supports modal windows.
- Supports dynamic SEO tags.

**Modernization notes**

- Uses global state.
- Uses short PHP opening tag.
- Central class of the whole application.
- Must not be refactored during Phase 1.
**Decision**


`PAGE` is the central legacy component of the application.

During Phase 1 it should be documented and analyzed, but not refactored.

---

## DB

**File**

```text
_core/_classes/classes.php
```

**Status**

- ✅ Verified

**Risk level**

- 🔴 Very High

**Purpose**

Central database access class.

**Responsibilities**

- Opens MySQL connection.
- Stores the active database connection.
- Executes SQL queries.
- Returns query results.
- Retrieves a single record.
- Escapes user input for SQL.
- Provides basic database utility functions.

**Main methods**

```php
DB::init()
DB::query()
DB::select()
DB::selectOne()
DB::func()
DB::secure()
```

**Dependencies**

- DB_SERVER
- DB_USER
- DB_PWD
- DB_NAME
- DB_CHARSET
- PAGE::$errors
- mysqli extension

**Confirmed behaviour**

- Uses `mysqli_connect()` to establish the connection.
- Sets the connection charset immediately after connecting.
- `query()` executes raw SQL.
- `select()` returns an array of rows.
- `select($sql, $key)` can build an associative array indexed by a field.
- `selectOne()` returns the first row or `false`.
- `func('insert_id')` returns the last inserted ID.
- `func('close')` closes the current connection.
- `secure()` wraps `mysqli_real_escape_string()`.

**Architecture observations**

- Uses a Singleton pattern.
- Database connection is stored as a static property.
- SQL statements are passed as plain strings.
- No prepared statements are implemented.
- Error handling is minimal and relies on `PAGE::$errors`.

**Modernization notes**

- Do not refactor during Phase 1.
- Security improvements should be introduced incrementally.
- Any change to this class affects the entire application.

**Decision**

`DB` is part of the legacy core.

During Phase 1 it should be documented first, tested second, and modernized only through small, isolated commits.

---

## USER

**File**

```text
_core/_classes/classes.php
```

**Status**

- 🔍 Under investigation

**Purpose**

Authentication and authorization.

**Responsibilities**

- Login.
- Logout.
- Session management.
- Admin access verification.

---

## FORM

**File**

```text
_core/_classes/classes.php
```

**Status**

- 🔍 Under investigation

**Purpose**

Dynamic form generation.

---

# 5. Helper Functions

---

## funcs.php

**File**

```text
_core/_functions/funcs.php
```

**Status**

- 🔍 Inventory in progress

**Known functions**

- secur()
- my_strip_tags()
- get_block()
- format_date()
- format_datetime()
- format_phone()
- sort_by()
- order_by()

**Findings**

- `create_function()` found.
- `sort_by()` — currently no usages found.
- `order_by()` — currently no usages found.

---

## auth.php

**File**

```text
_core/_functions/auth.php
```

**Status**

- 🔍 Inventory pending

**Responsibilities**

- Authentication helpers.
- Admin menu.
- Login form.
- User interface helpers.

---

# 6. Parser

---

## path.php

**Status**

- ✅ Verified

**Purpose**

- URL parsing.
- Building global `$_PATH`.

---

## page.php

**Status**

- ✅ Verified

**Purpose**

- Page loading.
- Runtime initialization.

---

# 7. PHP Compatibility Audit

## Confirmed

| Item | Status |
|------|--------|
| create_function() | ⚠️ Found |
| mysql_* | ✅ Not found |
| split() | ✅ Not found |
| each() | ✅ Not found |
| Short PHP tags | ⚠️ Found |

---

# 8. Dead Code Investigation

Current candidates:

| Component | Status |
|-----------|--------|
| sort_by() | 🔍 Candidate |
| order_by() | 🔍 Candidate |

Dead code is never removed until its absence of usage has been fully confirmed.

---

# 9. Inventory Rules

Every analyzed component should contain:

- File
- Purpose
- Responsibilities
- Dependencies
- Risk level
- Current status
- Modernization notes

---

# 10. Progress

| Component | Status |
|-----------|--------|
| PAGE | ✅ |
| DB | 🔍 |
| USER | 🔍 |
| FORM | 🔍 |
| Functions | 🔍 |
| Parser | 🔍 |
| Admin | ⏳ |
| AJAX | ⏳ |
| Templates | ⏳ |

---

# Status

This document is the working inventory of the Premium Avto codebase.

It is updated continuously throughout Phase 1 — Safe Modernization.