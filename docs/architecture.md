# Premium Avto — Architecture

> **Document:** Architecture  
> **Version:** 1.0  
> **Status:** Verified from legacy source code  
> **Purpose:** Explain the overall architecture of the Premium Avto legacy system.

---

# 1. Purpose

This document describes the architecture of the Premium Avto legacy system.

It is written for a developer who needs to understand the project quickly before making changes.

The goal is not to describe every file separately, but to explain:

- how the system receives a request;
- how the current page is determined;
- how content is loaded;
- how templates are applied;
- how the database is used;
- where the main logic is located.

---

# 2. System type

Premium Avto legacy is a custom PHP system.

It is not:

- WordPress;
- Laravel;
- Symfony;
- Yii;
- a standard CMS.

It is a small custom PHP framework / mini-CMS created specifically for this site.

The system has:

- custom routing;
- custom page rendering;
- custom template placeholders;
- custom database wrapper;
- custom authorization;
- custom form framework;
- admin-managed content blocks.

---

# 3. High-level system diagram

```text
Browser
   │
   ▼
HTTP Request
   │
   ▼
public_html/index.php
   │
   ▼
_core/_parser/path.php
   │
   ▼
global $_PATH
   │
   ▼
PAGE::getContent()
   │
   ▼
include(page index.html)
   │
   ▼
PAGE::$content
   │
   ▼
PAGE::html()
   │
   ▼
template:
landing.html / cabinet.html / print.html
   │
   ▼
placeholder replacement
   │
   ▼
DB::select() / DB::selectOne()
   │
   ▼
MySQL database
   │
   ▼
Final HTML
   │
   ▼
Browser
```

---

# 4. Main directories

Confirmed top-level project areas:

```text
_ajax/
_core/
admin/
public_html/
signin/
```

| Directory | Responsibility |
|-----------|----------------|
| `_core/` | Core framework: classes, config, routing, templates, helper functions |
| `public_html/` | Public entry point of the site |
| `admin/` | Administrative interface |
| `_ajax/` | AJAX endpoints |
| `signin/` | Authentication-related pages |

---

# 5. Core directory structure

The `_core` directory contains the main application logic.

```text
_core
│
├── _classes
│   └── classes.php
│
├── _config
│   └── config.php
│
├── _functions
│   ├── auth.php
│   └── funcs.php
│
└── _parser
    ├── page.php
    ├── path.php
    └── templates/
        ├── landing.html
        ├── cabinet.html
        └── print.html
```

---

# 6. Main runtime components

## 6.1 `config.php`

Contains global system configuration:

- database connection constants;
- cookie settings;
- domain name;
- email headers;
- global system messages;
- service variables.

Important confirmed constants:

```php
DB_SERVER
DB_NAME
DB_PORT
DB_USER
DB_PWD
DB_CHARSET

COOKIE_NAME
COOKIE_DOMAIN_NAME
COOKIE_DAYS
SESSION_TIMEOUT
COOKIE_IP_TIMEOUT

DOMAIN_NAME
ADMIN_EMAIL
```

---

## 6.2 `path.php`

Responsible for parsing the incoming route.

It reads:

```php
$_GET['link']
```

and builds:

```php
$_PATH
```

`$_PATH` is the central routing array used later by `PAGE::getContent()`.

---

## 6.3 `PAGE`

Central page lifecycle class.

Responsibilities:

- load page content;
- choose template;
- replace template placeholders;
- output final HTML.

Important methods:

```php
PAGE::init()
PAGE::getContent()
PAGE::html()
```

---

## 6.4 `DB`

Single database access class.

Responsibilities:

- connect to MySQL;
- execute SQL queries;
- return result arrays;
- escape strings.

Important methods:

```php
DB::init()
DB::query()
DB::select()
DB::selectOne()
DB::secure()
```

---

## 6.5 `USER`

Authentication and user state class.

Responsibilities:

- login;
- logout;
- session restoration;
- cookie-based auto-login;
- admin role checking.

Important methods:

```php
USER::init()
USER::isUser()
USER::isAdmin()
```

---

## 6.6 `auth.php`

Contains helper functions related to:

- login form;
- logout form;
- admin top menu;
- admin left menu;
- password hashes;
- cookie hashes.

---

## 6.7 `funcs.php`

Contains general helper functions.

Important confirmed functions:

```php
secur()
my_strip_tags()
get_block()
echo_table()
echo_yes()
echo_att()
echo_err()
echo_ajax()
my_mail()
```

`secur()` is one of the most important functions in the system because it is used to filter and sanitize input data.

---

# 7. Request lifecycle

The confirmed lifecycle of a normal request is:

```text
1. Browser opens URL.
2. Web server sends request to public_html/index.php.
3. path.php parses the requested URL from $_GET['link'].
4. path.php builds $_PATH.
5. PAGE::getContent() uses $_PATH.
6. PAGE::getContent() locates the required file.
7. That file is included with include().
8. Output buffering saves the generated content into PAGE::$content.
9. PAGE::html() loads the selected template.
10. Template placeholders are replaced.
11. Final HTML is printed.
12. Browser receives the response.
```

---

# 8. Template system

Premium Avto uses a custom placeholder-based template system.

Confirmed templates:

```text
_core/_parser/templates/landing.html
_core/_parser/templates/cabinet.html
_core/_parser/templates/print.html
```

Confirmed placeholders include:

```text
{#_TITLE_#}
{#_YEAR_#}
{#_SEO_#}
{#_CONTENT_#}
{#_BLOCK_1_#}
{#_BLOCK_2_#}
{#_BLOCK_3_#}
{#_BLOCK_4_#}
{#_BLOCK_5_#}
{#_BLOCK_6_#}
{#_TOP_MENU_#}
{#_LEFT_MENU_#}
{#_SIGNIN_#}
{#_MODAL_#}
{#_MODAL_S_#}
```

Replacement is performed with `str_replace()` inside `PAGE::html()`.

---

# 9. Public landing page architecture

The public page is assembled from the landing template and database blocks.

```text
landing.html
   │
   ├── {#_BLOCK_1_#} → get_block(1) → texts
   ├── {#_BLOCK_2_#} → get_block(2) → texts
   ├── {#_BLOCK_3_#} → get_block(3) → gallery
   ├── {#_BLOCK_4_#} → get_block(4) → texts
   ├── {#_BLOCK_5_#} → get_block(5) → texts
   └── {#_BLOCK_6_#} → get_block(6) → texts
```

Confirmed block meanings from the admin menu:

| Block | Admin section | Data source |
|-------|---------------|-------------|
| 1 | Почему мы? | `texts` |
| 2 | Услуги | `texts` |
| 3 | Фотогалерея | `gallery` |
| 4 | Контакты | `texts` |
| 5 | О компании | `texts` |
| 6 | Партнеры | `texts` |

---

# 10. Administrative architecture

The admin area uses the `cabinet.html` template.

For admin pages, `PAGE::html()` inserts:

```text
{#_TOP_MENU_#}
{#_LEFT_MENU_#}
{#_SIGNIN_#}
```

These are generated by functions from `auth.php`:

```php
get_top_menu()
get_left_menu()
get_login_form()
```

Admin menu confirms that the site content is managed through sections:

```text
/admin/block1/
/admin/block2/
/admin/block3/
/admin/block4/
/admin/block5/
/admin/block6/
/admin/seo/
```

---

# 11. Database architecture

The project uses MySQL/MariaDB through the custom `DB` class.

Confirmed database name:

```text
detalauto_pa
```

Confirmed tables used by analyzed code:

```text
texts
gallery
users
seo
```

Confirmed usage:

| Table | Usage |
|-------|-------|
| `texts` | Landing page text blocks |
| `gallery` | Photo gallery |
| `users` | Authentication |
| `seo` | SEO data managed from admin |

---

# 12. Security architecture

Security is implemented through several layers.

```text
Input
   │
   ▼
secur()
   │
   ▼
DB::secure() / mysqli_real_escape_string()
   │
   ▼
SQL
```

Confirmed security-related elements:

- `secur()` filters user input by type;
- `my_strip_tags()` removes scripts and dangerous fragments;
- `USER` validates session/cookie state;
- password checks are centralized in `auth.php`;
- admin access is checked with `USER::isAdmin()`.

Known legacy security risks are documented in `technical-debt.md`.

---

# 13. Architectural strengths

Confirmed strengths:

- centralized page rendering;
- centralized database access;
- centralized authentication;
- centralized input filtering;
- database-driven landing page;
- admin-editable content;
- reusable form framework;
- simple template system;
- clear separation between config, classes, functions and parser.

These strengths make gradual modernization possible without rewriting the site from scratch.

---

# 14. Architectural limitations

Known limitations:

- old PHP style;
- short PHP tags;
- no namespaces;
- no Composer autoloading;
- no prepared statements;
- password hashing still uses legacy MD5 mechanism;
- `create_function()` exists in helper functions;
- configuration and UI messages are mixed in one file.

These limitations should be fixed gradually, with one safe change per commit.

---

# 15. For developers

## If you need to change the public homepage

Look at:

```text
_core/_parser/templates/landing.html
_core/_functions/funcs.php → get_block()
database table texts
database table gallery
```

## If you need to change admin navigation

Look at:

```text
_core/_functions/auth.php
get_top_menu()
get_left_menu()
```

## If you need to change routing

Look at:

```text
_core/_parser/path.php
PAGE::getContent()
```

## If you need to change database logic

Look at:

```text
_core/_classes/classes.php
class DB
```

## If you need to change authentication

Look at:

```text
_core/_classes/classes.php
class USER

_core/_functions/auth.php

table users
```

## If you need to change templates

Look at:

```text
_core/_parser/templates/
```

---

# 16. Recommended modernization strategy

The architecture supports gradual modernization.

Recommended order:

```text
1. Preserve current behavior.
2. Add documentation.
3. Add testing checklist.
4. Remove high-risk legacy issues.
5. Improve PHP compatibility.
6. Improve frontend.
7. Improve security.
8. Improve internal architecture only after behavior is stable.
```

Do not rewrite the project from scratch unless the current architecture becomes a real blocker.

---
# 17. CMS Admin Modules

The administrative part of the system is organized as a set of small modules under the `admin/` directory.

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

Important implementation detail:

Although admin module files use the `.html` extension, they contain executable PHP code and are included by the legacy page loader.

Examples:

```text
admin/block1/index.html
admin/block2/index.html
admin/seo/index.html
```

## Content modules

The content modules are:

```text
block1
block2
block4
block5
block6
```

They use the `texts` table.

Confirmed mapping:

| Module | Admin title | Database table | Block value |
|--------|-------------|----------------|-------------|
| block1 | Блок "Почему мы?" | texts | 1 |
| block2 | Блок "Услуги" | texts | 2 |
| block4 | Блок "Контакты" | texts | 4 |
| block5 | Блок "О компании" | texts | 5 |
| block6 | Блок "Партнеры" | texts | 6 |

Confirmed behaviour:

- each module sets its page title with `PAGE::setTitle()`;
- each module defines a numeric `$block` value;
- records are selected from `texts` by `block`;
- records are inserted into `texts`;
- records are updated in `texts`;
- active/inactive state is stored in the `active` field;
- admin URLs follow the pattern `/admin/blockX/`.

The modules have similar structure but are not completely identical. Some use different input controls and text handling.

## Gallery module

The gallery module is:

```text
block3
```

It corresponds to the public gallery section and is expected to use the `gallery` table.

This module should be documented separately after direct inspection.

## SEO module

The SEO module is located at:

```text
admin/seo/index.html
```

It uses the `seo` table.

Confirmed behaviour:

- edits one SEO record with `id = 1`;
- updates `title`;
- updates `description`;
- updates `keywords`;
- uses the form framework;
- saves data through `DB::query()`.

## Users module

The users module is located at:

```text
admin/users/index.html
```

It corresponds to administrator/user management and is expected to use the `users` table.

This module should be documented separately after direct inspection.

## Architectural conclusion

The admin area is not a single monolithic file.

It is a lightweight module-based CMS built on top of the legacy core:

```text
URL
  ↓
path.php
  ↓
PAGE::getContent()
  ↓
admin module index.html
  ↓
DB
  ↓
database table
```

This structure is suitable for gradual modernization.

Future improvements should preserve the existing module boundaries unless a separate refactoring plan is created.
 
 ---
# 18. Related documents

Read together with:

- `core.md`
- `routes.md`
- `database.md`
- `technical-debt.md`
- `changes-for-start-in-laragon.md`

---

# 19. Status

This document reflects the confirmed architecture of the Premium Avto legacy system at the end of the reverse engineering stage.

It should be updated only when new verified information is discovered in the source code.

