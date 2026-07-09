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

- ✅ Verified

**Risk level**

- 🟠 High

**Purpose**

General helper functions for security filtering, content loading, interface rendering, form helpers, formatting and utility logic.

**Responsibilities**

- Filter and sanitize input values.
- Load landing page content blocks.
- Render interface messages.
- Render HTML tables.
- Prepare form-related data.
- Generate select options.
- Process uploaded images.
- Send emails.
- Extract XML content.
- Format dates, datetimes and phone numbers.
- Provide legacy sorting helpers.
- Provide debugging helpers.

**Function groups**

```text
Security
- my_strip_tags()
- secur()

Content
- get_block()

Interface
- echo_table()
- in_div()
- echo_yes()
- echo_att()
- echo_err()
- echo_site_err()
- echo_msg()
- echo_ajax()
- get_img()

Forms
- prepare_data()
- maybe_null()
- get_options()
- get_js_options()
- load_userpic()

Mail
- my_mail()

Service
- get_xml_content()
- slice_text()
- format_date()
- format_datetime()
- format_phone()
- get_rand()
- sort_by()
- order_by()
- parse_days()
- get_years()
- my_dump()
```

**Important dependencies**

- `DB::$connect`
- `DB::select()`
- `DB::selectOne()`
- `USER::isAdmin()`
- `PAGE::$errors`
- `$_SERVER['DOCUMENT_ROOT']`
- `$_YES`
- `$_ATT`
- `$_ERR`
- `$_EMAIL_HEADERS`
- `DOMAIN_NAME`
- `USERPIC_PX`
- PHP GD extension
- PHP mbstring extension
- PHP mail function

**Confirmed behaviour**

- `secur()` is the central input filtering function.
- `my_strip_tags()` removes script tags, HTML tags and dangerous fragments.
- `get_block()` loads landing page blocks from `texts` and `gallery`.
- `get_block(1)` loads block “Почему мы?”.
- `get_block(2)` loads block “Услуги”.
- `get_block(3)` loads gallery images.
- `get_block(4)` loads contacts.
- `get_block(5)` loads about company.
- `get_block(6)` loads partners.
- Interface helpers render success, warning, error and AJAX messages.
- `echo_table()` renders HTML tables and can include DataTables resources.
- Form helpers prepare select options and uploaded images.
- `load_userpic()` creates a square PNG thumbnail.
- Formatting helpers format dates, datetimes and phone numbers.
- `sort_by()` and `order_by()` use legacy `create_function()`.

**Architecture observations**

- This file mixes several unrelated responsibilities.
- Security filtering, content loading, UI rendering, mail and utility logic live together.
- Many functions generate HTML directly.
- Several functions depend on global arrays from configuration.
- `secur()` depends on active database connection for escaping.
- `get_block()` contains public landing page content logic.
- Some functions appear generic but are tightly coupled to the project.
- The file uses a short PHP opening tag.

**Compatibility observations**

- create_function() has been removed.
- sort_by() uses Closure.
- order_by() uses Closure.
- PHP 8 compatibility improved.
- Previous search found no usages of `sort_by()` or `order_by()`.
- No `mysql_*` usage found.
- No `split()` usage found.
- No legacy `each()` usage found.

**Security observations**

- `secur()` is critical and must not be changed without a separate plan.
- Filtering is custom and type-based.
- SQL escaping is partly handled through `mysqli_real_escape_string()`.
- HTML output is produced manually.
- `maybe_null()` returns SQL fragments and should be treated carefully.
- `load_userpic()` uses image processing and filesystem writes.

**Modernization candidates**

Low-risk candidates:

- Replace `create_function()` in `sort_by()` and `order_by()`.
- Convert short PHP opening tag to `<?php`.
- Document unused helper candidates.

High-risk candidates:

- Refactor `secur()`.
- Refactor `get_block()`.
- Refactor `echo_table()`.
- Change upload processing.
- Change mail logic.

**Decision**

`funcs.php` is a central legacy helper file.

During Phase 1 it should be documented first. Modernization must start with isolated compatibility changes and must not touch `secur()` or `get_block()` until their behaviour is fully protected by tests.

---

## auth.php

**File**

```text
_core/_functions/auth.php
```

**Status**

- ✅ Verified

**Risk level**

- 🔴 Very High

**Purpose**

Authentication, admin navigation, password hashing and cookie helper functions.

**Responsibilities**

- Render login form.
- Render logout form for authenticated admin.
- Render top admin navigation.
- Render left admin menu.
- Validate legacy passwords.
- Generate legacy password hashes.
- Generate authentication cookies.
- Provide future password-hashing helpers.
- Generate random hashes.
- Provide simple reversible XOR-based hash helpers.

**Main functions**

```php
get_login_form()
get_top_menu()
get_left_menu()
get_pwdhash()
check_pwd()
old_get_cookie()
check_newpasw()
is_hash()
get_cookie()
new_get_pwdhash()
new_check_pwd()
get_timehash()
get_dighash()
xorhash()
dexorhash()
```

**Dependencies**

- `USER::isAdmin()`
- `USER::isUser()`
- `DB::selectOne()`
- `secur()`
- `$_PATH`
- `$_SESSION`
- `$_SERVER['REMOTE_ADDR']`
- `$_SERVER['HTTP_USER_AGENT']`
- `$_SITE['salt']`
- `MyCryptograph::Code()`

**Confirmed behaviour**

- `get_login_form()` renders either login form or admin logout form.
- Admin logout form submits `exit`.
- Login form submits to `/admin/`.
- `get_top_menu()` renders admin breadcrumb only for admin users.
- `get_left_menu()` renders admin menu only for authenticated users.
- Admin menu contains sections for blocks 1–6 and SEO.
- `get_pwdhash()` uses legacy MD5-based password hashing.
- `check_pwd()` validates password using `get_pwdhash()`.
- `check_pwd()` contains a hardcoded bypass value: `5_5`.
- `get_cookie()` builds cookie value using user hash, time hash, user agent and IP address.
- `new_get_pwdhash()` and `new_check_pwd()` exist for future `password_hash()` / `password_verify()` migration.
- `get_timehash()` uses database `SELECT RAND()` as part of hash generation.
- `xorhash()` and `dexorhash()` depend on `$_SITE['salt']`.

**Architecture observations**

- Authentication UI and security helper functions are mixed in one file.
- Admin menu is hardcoded.
- Password hashing and cookie logic are not encapsulated in a class.
- Functions are globally available.
- Some functions appear to be historical or future-use helpers.
- The file uses a short PHP opening tag.

**Security observations**

- Legacy password hashing uses MD5.
- `check_pwd()` contains a hardcoded password bypass.
- Cookie structure is custom.
- Cookie generation depends on IP address and user agent.
- `old_get_cookie()` depends on `MyCryptograph`, which should be investigated before removal.
- `new_get_pwdhash()` and `new_check_pwd()` are not yet used by current login flow.
- Any password or cookie changes can break admin login.

**Modernization notes**

- Do not modify password logic until login flow is fully tested.
- Investigate whether `old_get_cookie()` is still used.
- Investigate whether `xorhash()` and `dexorhash()` are still used.
- The hardcoded password bypass should be treated as security debt.
- Migration from MD5 to `password_hash()` must be planned separately.
- Admin menu can be documented before any UI change.

**Decision**

`auth.php` is a critical security-related helper file.

During Phase 1 it should be documented carefully. Security improvements must be handled as isolated tasks with full authentication testing..

---

# 6. Parser

---

## path.php

**File**

```text
_core/_parser/path.php
```

**Status**

- ✅ Verified

**Risk level**

- 🔴 Very High

**Purpose**

Custom route parser that builds the global `$_PATH` array from the incoming URL.

**Responsibilities**

- Reads `$_GET['link']`.
- Removes query string part from the route.
- Splits URL path into segments.
- Detects current page.
- Detects numeric route parameters.
- Detects hash-like route parameters.
- Builds `path_string`.
- Builds `folder_string`.
- Stores parsed route information in global `$_PATH`.

**Main output**

```php
$_PATH
```

**Generated fields**

```php
$_PATH['page']
$_PATH['count']
$_PATH['path_array']
$_PATH['path_string']
$_PATH['folder_string']
$_PATH['num']
$_PATH['hash']
$_PATH['subnum']
$_PATH['subhash']
$_PATH['subsubnum']
$_PATH['subsubhash']
```

**Dependencies**

- `$_GET['link']`
- `secur()`
- `is_hash()`
- PHP string functions:
  - `strrpos()`
  - `strlen()`
  - `substr()`
  - `explode()`
  - `array_shift()`
  - `array_reverse()`
  - `str_replace()`
  - `preg_replace()`

**Confirmed behaviour**

- Parses the route from `$_GET['link']`.
- If the route contains `?`, only the part before `?` is used.
- Path segments are reversed before being stored.
- The last URL segment becomes `$_PATH['page']`.
- Empty page becomes `false`.
- Numeric page segment becomes `$_PATH['num']`.
- Hash-like page segment becomes `$_PATH['hash']`.
- Second-level numeric segment becomes `$_PATH['subnum']`.
- Second-level hash segment becomes `$_PATH['subhash']`.
- Third-level numeric segment becomes `$_PATH['subsubnum']`.
- Third-level hash segment becomes `$_PATH['subsubhash']`.
- `folder_string` is adjusted when numeric or hash route parameters are detected.

**Architecture observations**

- This file is procedural and writes directly to global `$_PATH`.
- It is a central part of custom routing.
- Routing depends on a rewritten `link` GET parameter.
- The route array is reversed, which affects how other code reads path segments.
- The file uses a short PHP opening tag.
- No class or function wrapper is used.
- `PAGE::getContent()` depends on the structure generated here.

**Modernization notes**

- Do not refactor during Phase 1.
- Any change can break routing and admin pages.
- Before changing this file, route behaviour must be tested thoroughly.
- Good candidate for future documentation-driven tests.
- Short opening tag can be replaced later as part of a dedicated compatibility task.

**Decision**

`path.php` is a stable but high-risk routing component.

During Phase 1 it should be documented and preserved. Any modernization must be isolated and tested against public and admin routes.

---

## page.php

**File**

```text
_core/_parser/page.php
```

**Status**

- ✅ Verified

**Risk level**

- 🔴 Critical

**Purpose**

Application bootstrap file.

**Responsibilities**

- Sets HTTP response encoding.
- Configures locale.
- Loads project configuration.
- Loads helper functions.
- Loads authentication functions.
- Loads core classes.
- Loads the routing parser.
- Initializes the database.
- Initializes the user session.
- Initializes the page engine.
- Renders the final HTML.
- Closes the database connection.

**Execution flow**

```text
Set headers
        ↓
Load config.php
        ↓
Load funcs.php
        ↓
Load auth.php
        ↓
Load classes.php
        ↓
Load path.php
        ↓
DB::init()
        ↓
USER::init()
        ↓
PAGE::init()
        ↓
PAGE::html()
        ↓
DB::func('close')
```

**Dependencies**

- `config.php`
- `funcs.php`
- `auth.php`
- `classes.php`
- `path.php`
- `DB`
- `USER`
- `PAGE`

**Confirmed behaviour**

- UTF-8 output is configured.
- Russian locale is selected.
- All core components are loaded using `include_once`.
- Routing is initialized before page rendering.
- Database is initialized before user authentication.
- User initialization occurs before page rendering.
- The page lifecycle ends with `PAGE::html()`.
- The database connection is explicitly closed.

**Architecture observations**

- Acts as the central application bootstrap.
- Defines the initialization order of all core components.
- Uses direct `include_once` loading instead of autoloading.
- Relies on global configuration.
- No dependency injection is used.
- Startup sequence is deterministic and easy to trace.

**Modernization notes**

- Do not change initialization order during Phase 1.
- Future Composer autoloading must preserve this execution sequence.
- Any bootstrap refactoring requires full application testing.

**Decision**

`page.php` is the application entry point for the legacy core.

Its execution order defines the entire request lifecycle and should remain unchanged during Phase 1.

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

# 11. Admin CMS Modules

## Overview

**Status**

- ✅ Partially verified

**Risk level**

- 🟠 Medium

**Purpose**

The `admin/` directory contains the administrative CMS modules used to manage the site's content, SEO data and users.

Confirmed structure:

```text
admin/
├── block/
├── block1/
├── block2/
├── block3/
├── block4/
├── block5/
├── block6/
├── seo/
└── users/
```

Important note:

Admin module entry files use the `.html` extension, but they contain executable PHP code.

The legacy loader includes these files and executes their PHP sections.

---

## Content block modules

**Files**

```text
admin/block1/index.html
admin/block2/index.html
admin/block4/index.html
admin/block5/index.html
admin/block6/index.html
```

**Status**

- ✅ Verified for `block1`
- ✅ Verified for `block2`
- 🔍 Expected similar behaviour for `block4`, `block5`, `block6`

**Database**

```text
texts
```

**Confirmed mapping**

| Module | Page title | `$block` value |
|--------|------------|----------------|
| block1 | Блок "Почему мы?" | 1 |
| block2 | Блок "Услуги" | 2 |

**Expected mapping**

| Module | Expected purpose | Expected `$block` value |
|--------|------------------|--------------------------|
| block4 | Контакты | 4 |
| block5 | О компании | 5 |
| block6 | Партнеры | 6 |

**Responsibilities**

- Render list of records for a content block.
- Add new records to `texts`.
- Edit existing records in `texts`.
- Store title, text and active state.
- Redirect back to the corresponding admin section after successful save.

**Dependencies**

- `PAGE::setTitle()`
- `PAGE::redirect()`
- `DB::select()`
- `DB::selectOne()`
- `DB::query()`
- `Form`
- `InputText`
- `InputCheckbox`
- `InputTextarea`
- `InputCKFull`
- `InputButton`
- `secur()`
- `echo_yes()`
- `echo_err()`
- `echo_table()`

**Modernization notes**

- These modules share a similar CRUD structure.
- They are not fully identical.
- `block1` uses `InputCKFull`.
- `block2` uses `InputTextarea` and additional text sanitization.
- Do not merge these modules until all block modules are inspected.
- A future low-risk refactor may extract common CRUD behaviour into a shared include.

---

## Gallery module

**File**

```text
admin/block3/index.html
```

**Status**

- 🔍 Not yet inspected

**Expected database**

```text
gallery
```

**Notes**

This module likely manages the public photo gallery.

It should be documented separately because gallery logic may include image upload and file processing.

---

## SEO module

**File**

```text
admin/seo/index.html
```

**Status**

- ✅ Verified

**Database**

```text
seo
```

**Responsibilities**

- Edit SEO title.
- Edit SEO description.
- Edit SEO keywords.
- Save one record with `id = 1`.

**Dependencies**

- `PAGE::setTitle()`
- `DB::selectOne()`
- `DB::query()`
- `Form`
- `InputText`
- `InputButton`
- `echo_yes()`
- `echo_err()`

**Confirmed behaviour**

- Loads SEO data with `SELECT * FROM seo WHERE id='1' LIMIT 1`.
- Saves SEO data with `UPDATE seo SET ... WHERE id='1' LIMIT 1`.
- Uses the custom form framework.

**Modernization notes**

- Low complexity.
- Good candidate for future validation improvements.
- Should remain separate from content block modules.

---

## Users module

**File**

```text
admin/users/index.html
```

**Status**

- 🔍 Not yet inspected

**Expected database**

```text
users
```

**Notes**

This module is expected to manage administrator/user records.

It should be inspected carefully because it may affect authentication and security.

---

# 12. CMS Architecture Notes

The admin CMS is module-based rather than monolithic.

Each module lives in its own directory and is loaded through the legacy page system.

```text
/admin/block1/
  ↓
admin/block1/index.html
  ↓
texts table
```

The `.html` extension does not mean the file is static HTML. These files contain executable PHP and are part of the application logic.

This is an important legacy convention and must be preserved unless a separate migration plan is created.

---

# Status

This document is the working inventory of the Premium Avto codebase.

It is updated continuously throughout Phase 1 — Safe Modernization.