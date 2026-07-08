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
# 4. Core Dependency Map

This diagram shows the confirmed high-level dependencies of the legacy core.

```text
HTTP Request
    |
    v
public_html/index.php
    |
    v
_core/_parser/path.php
    |
    v
global $_PATH
    |
    v
PAGE
    |
    +-- USER
    |
    +-- DB
    |
    +-- auth.php
    |     |
    |     +-- get_top_menu()
    |     +-- get_left_menu()
    |     +-- get_login_form()
    |
    +-- funcs.php
    |     |
    |     +-- get_block()
    |     +-- secur()
    |
    +-- templates
          |
          +-- landing.html
          +-- cabinet.html
          +-- print.html
```

Notes:

- `PAGE` is the central lifecycle component.
- `DB` is the central database access component.
- `USER` controls authentication and authorization.
- `auth.php` provides admin and login UI helpers.
- `funcs.php` provides general helper functions and content block loading.
- Templates are filled by `PAGE::html()` using placeholder replacement.

---


# 5. Core Classes

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

- ✅ Verified

**Risk level**

- 🔴 Very High

**Purpose**

Central authentication and authorization class.

**Responsibilities**

- Handles admin login.
- Handles logout.
- Restores user session.
- Restores user state from cookie.
- Stores current user ID.
- Stores current user hash.
- Stores current user role.
- Checks whether the current user is an administrator.

**Main methods**

```php
USER::init()
USER::isUser()
USER::isAdmin()
```

**Internal methods**

```php
USER::getUser()
```

**Dependencies**

- `$_POST`
- `$_GET`
- `$_SESSION`
- `$_COOKIE`
- `$_SERVER['REMOTE_ADDR']`
- `DB::selectOne()`
- `DB::query()`
- `PAGE::redirect()`
- `secur()`
- `check_pwd()`
- `get_cookie()`
- `COOKIE_NAME`
- `COOKIE_DAYS`
- `SESSION_TIMEOUT`

**Confirmed behaviour**

- Login is triggered by `$_POST['enter']`.
- Login requires `email` and `pwd`.
- User lookup is performed in the `users` table by email.
- Password verification is delegated to `check_pwd()`.
- Only active users can log in.
- Successful admin login redirects to `/admin/`.
- Failed login redirects to `/`.
- Logout clears the project cookie and PHP session cookie.
- Logout clears all session values.
- Auto-login attempts to restore user state from session or cookie.
- Admin access is confirmed by `USER::isAdmin()`.
- `USER::isAdmin()` requires a valid user and role equal to `admin`.

**Architecture observations**

- Uses a Singleton pattern.
- Authentication logic is executed inside the constructor.
- Uses global PHP superglobals directly.
- Session and cookie management are mixed in the same class.
- SQL queries are built as raw strings.
- Login, logout, auto-login, and role checking are tightly coupled.
- Depends on helper functions from `auth.php` and `funcs.php`.

**Security observations**

- Password validation depends on legacy `check_pwd()`.
- Cookie authentication depends on legacy `get_cookie()`.
- SQL escaping depends on `secur()` and current query construction.
- IP address is stored and checked using `INET_ATON()`.
- Cookie parsing currently does not visibly validate all hash parts before restoring the user.

**Modernization notes**

- Do not refactor during early Phase 1.
- Any change can break admin access.
- Before modifying this class, document `check_pwd()` and `get_cookie()`.
- Authentication modernization must be done in very small commits.
- Password hashing should not be changed until login behaviour is fully covered by testing.

**Decision**

`USER` is a critical legacy core component.

During Phase 1 it should be documented carefully and changed only after the authentication flow is fully understood and tested.
---

## FORM

**File**

```text
_core/_classes/classes.php
```

**Status**

- ✅ Verified

**Risk level**

- 🟠 High

**Purpose**

Dynamic form container class.

**Responsibilities**

- Stores form identity.
- Stores form method.
- Stores form action.
- Collects temporary input objects from `$_TMP_FORM_INPUTS`.
- Applies style and extra attributes to all form inputs.
- Captures submitted values.
- Detects required field errors.
- Detects file inputs and enables multipart form encoding.
- Renders opening and closing `<form>` tags.
- Switches form inputs into read-only mode.

**Main methods**

```php
Form::__construct()
Form::setAttr()
Form::setInputsStyle()
Form::setInputsExt()
Form::catchValues()
Form::resetValues()
Form::html()
Form::readOnly()
```

**Dependencies**

- global `$_TMP_FORM_INPUTS`
- `secur()`
- `$_POST`
- `$_GET`
- input classes:
  - `InputDateOld`
  - `InputPlace`
  - `InputMetro`
  - `InputSelectMulti`
  - `InputFile`
  - `InputButton`
  - `InputHidden`

**Confirmed behaviour**

- Constructor collects inputs from global `$_TMP_FORM_INPUTS`.
- After form creation, `$_TMP_FORM_INPUTS` is reset.
- Supported methods are `post`, `get`, `both`, and `session`.
- Unknown method defaults to `post`.
- `catchValues()` captures submitted input values.
- `catchValues()` returns an associative array of input values.
- Required fields set `required_flag` when missing.
- File inputs add `enctype="multipart/form-data"` to the form.
- `html('open')` renders opening form markup.
- `html('close')` renders closing form markup.
- `readOnly()` hides the form wrapper and disables inputs.

**Architecture observations**

- Class name is `Form`, not `FORM`.
- Uses global temporary input storage.
- Form rendering and value processing are mixed in one class.
- Depends on many input subclasses.
- Uses `get_class()` branching for special input types.
- HTML is generated directly inside the class.
- The class is part of a larger custom form framework.

**Modernization notes**

- Do not refactor during early Phase 1.
- Before changing this class, all input classes must be inventoried.
- Any form changes may affect admin editing screens.
- Required field behaviour should be tested before modification.
- File upload behaviour should be tested before modification.

**Decision**

`Form` is an important legacy UI infrastructure component.

During Phase 1 it should be documented and preserved until the full form/input framework is understood.

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