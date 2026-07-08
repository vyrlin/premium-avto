# Premium Avto — Routing

> **Document:** Routing  
> **Version:** 1.0  
> **Status:** Verified from legacy source code  
> **Purpose:** Explain how URLs are processed and how pages are loaded.

---

# 1. Purpose

This document explains the routing mechanism of the Premium Avto legacy system.

It describes:

- how the incoming URL is received;
- how the route is parsed;
- how the global `$_PATH` array is built;
- how `PAGE::getContent()` uses routing data;
- how the correct page file is included;
- how the final HTML is generated.

---

# 2. Routing overview

Premium Avto uses a custom routing mechanism.

It does not use a standard PHP framework router.

The routing flow is:

```text
Browser URL
   │
   ▼
public_html/index.php
   │
   ▼
_core/_parser/path.php
   │
   ▼
$_GET['link']
   │
   ▼
$_PATH
   │
   ▼
PAGE::getContent()
   │
   ▼
include(page file)
   │
   ▼
PAGE::html()
   │
   ▼
HTML response
```

---

# 3. Main routing files

Confirmed routing-related files:

```text
public_html/index.php
_core/_parser/path.php
_core/_parser/page.php
_core/_parser/templates/
```

| File | Responsibility |
|------|----------------|
| `public_html/index.php` | Public entry point |
| `_core/_parser/path.php` | Parses URL and builds `$_PATH` |
| `_core/_parser/page.php` | Contains `PAGE` logic |
| `_core/_parser/templates/` | Contains output templates |

---

# 4. Source of the route

The route is read from:

```php
$_GET['link']
```

This means that the web server or `.htaccess` forwards the requested URL to PHP through the `link` parameter.

Example logical form:

```text
/index.php?link=/admin/block1/
```

The exact rewrite rule should be checked in `.htaccess`.

---

# 5. Query string removal

`path.php` removes the query string before parsing the route.

Example:

```text
/admin/block1/?test=1
```

becomes:

```text
/admin/block1/
```

This means routing is based only on the path part of the URL.

---

# 6. Path parsing

The route string is split by `/`.

Then the first empty element is removed and the path array is reversed.

Simplified logic:

```php
$path = explode('/', $string);
array_shift($path);
$path = array_reverse($path);
```

This means the last part of the URL becomes the first element of the internal path array.

---

# 7. The `$_PATH` array

`path.php` creates a global array:

```php
$_PATH
```

This is the central routing structure of the system.

Confirmed fields:

| Field | Meaning |
|-------|---------|
| `page` | Current page segment |
| `count` | Number of path segments |
| `path_array` | Reversed path array |
| `path_string` | Full route path |
| `folder_string` | Route folder path |
| `num` | Numeric ID from the last segment |
| `hash` | Hash value from the last segment |
| `subnum` | Numeric ID from the previous segment |
| `subhash` | Hash value from the previous segment |
| `subsubnum` | Numeric ID from the third segment |
| `subsubhash` | Hash value from the third segment |

---

# 8. Numeric parameters

If the last URL segment is numeric, it is stored as:

```php
$_PATH['num']
```

Example:

```text
/item/25/
```

can produce:

```php
$_PATH['num'] = 25;
```

The system also supports:

```php
$_PATH['subnum']
$_PATH['subsubnum']
```

---

# 9. Hash parameters

If a URL segment is a valid hash, it can be stored as:

```php
$_PATH['hash']
$_PATH['subhash']
$_PATH['subsubhash']
```

Before hash validation, `.html` is removed from the last segment.

This means the routing layer supports URLs that may end with `.html`.

---

# 10. Folder string adjustment

When `subnum`, `subhash`, `subsubnum` or `subsubhash` is detected, `path.php` adjusts:

```php
$_PATH['folder_string']
```

This allows the system to separate:

- route folder;
- page;
- numeric parameters;
- hash parameters.

This is important for `PAGE::getContent()` because it uses `folder_string` when searching for `index.html`.

---

# 11. Page loading through `PAGE::getContent()`

After `$_PATH` is built, page loading happens in `PAGE::getContent()`.

Confirmed logic:

```text
If $_PATH['page'] exists:
    try to include $_CORE_ROOT . $_PATH['path_string']

Else:
    try to include $_CORE_ROOT . $_PATH['path_string'] . 'index.html'

If file does not exist:
    try to include $_CORE_ROOT . $_PATH['folder_string'] . 'index.html'

If still not found:
    include $_CORE_ROOT . '/404.html'
```

---

# 12. Access control in routing

Inside `PAGE::getContent()` access is checked for the admin section.

Confirmed logic:

```text
If current directory is admin:
    allow only USER::isAdmin()
Else:
    allow request
```

If access is denied:

```text
Redirect to /signin/
```

This means admin access protection is implemented at the page-loading level.

---

# 13. Template selection

After content is loaded, `PAGE::html()` selects a template.

Confirmed default logic:

```text
If current directory is admin:
    template = cabinet

If current directory is signin:
    template = cabinet

Otherwise:
    template = landing
```

Confirmed templates:

```text
_core/_parser/templates/landing.html
_core/_parser/templates/cabinet.html
_core/_parser/templates/print.html
```

---

# 14. Full request lifecycle

Complete confirmed flow:

```text
1. Browser requests a URL.

2. Web server forwards the request to public_html/index.php.

3. The requested route is available as $_GET['link'].

4. _core/_parser/path.php removes query string from the route.

5. path.php splits the route into segments.

6. path.php builds global $_PATH.

7. PAGE::getContent() reads $_PATH.

8. PAGE::getContent() checks admin access if needed.

9. PAGE::getContent() searches for the page file.

10. The page file is included through include().

11. Output buffering stores generated content in PAGE::$content.

12. PAGE::html() chooses the template.

13. Template placeholders are replaced.

14. Final HTML is printed to the browser.
```

---

# 15. Routing examples

## Public homepage

Logical route:

```text
/
```

Expected behavior:

```text
Load public content

Use landing template

Insert landing blocks
```

---

## Admin block page

Logical route:

```text
/admin/block1/
```

Expected behavior:

```text
Check USER::isAdmin()

Load admin block page

Use cabinet template

Insert admin menu
```

---

## Numeric page parameter

Logical route:

```text
/admin/item/25/
```

Possible internal value:

```php
$_PATH['num'] = 25
```

---

## Hash page parameter

Logical route:

```text
/some-page/abcdef1234567890abcdef1234567890/
```

Possible internal value:

```php
$_PATH['hash'] = 'abcdef1234567890abcdef1234567890'
```

---

# 16. Template placeholders related to routing

For public pages:

```text
{#_CONTENT_#}
{#_BLOCK_1_#}
{#_BLOCK_2_#}
{#_BLOCK_3_#}
{#_BLOCK_4_#}
{#_BLOCK_5_#}
{#_BLOCK_6_#}
```

For admin pages:

```text
{#_CONTENT_#}
{#_TOP_MENU_#}
{#_LEFT_MENU_#}
{#_SIGNIN_#}
```

`PAGE::html()` decides which placeholders to replace depending on the active template.

---

# 17. Important routing dependencies

Routing depends on:

```text
$_GET['link']
$_PATH
PAGE::getContent()
USER::isAdmin()
secur()
is_hash()
```

Therefore, changes to these components can affect the entire site.

---

# 18. Developer guide

## If you need to add a new page

Recommended pattern:

```text
1. Create a folder for the route.
2. Add index.html inside it.
3. Let PAGE::getContent() load it automatically.
4. Use existing templates and helper functions.
```

---

## If you need to debug a wrong page

Check:

```text
$_GET['link']
$_PATH['path_string']
$_PATH['folder_string']
$_PATH['page']
```

Then check whether the target file exists.

---

## If admin page redirects to signin

Check:

```text
USER::isAdmin()
USER::isUser()
session
cookie
table users
```

---

## If a page shows 404

Check:

```text
$_PATH['path_string']
$_PATH['folder_string']
target index.html
404.html fallback
```

---

# 19. Known routing risks

## 19.1 Dependency on `$_GET['link']`

If `link` is missing or malformed, routing may fail.

The `.htaccess` rule should be preserved carefully.

---

## 19.2 Dynamic include

The system includes files based on route data.

This is normal for this legacy system, but requires careful input filtering.

Before changing this behavior, verify how `secur()` handles path-related input.

---

## 19.3 Global state

Routing relies on the global variable:

```php
$_PATH
```

This is simple but makes the routing state globally mutable.

Do not rename or restructure `$_PATH` without a full compatibility check.

---

# 20. Safe modernization recommendations

Routing should be modernized carefully.

Recommended order:

```text
1. Document current routes.
2. Add route testing checklist.
3. Preserve current URL behavior.
4. Improve validation around $_GET['link'].
5. Only then consider refactoring routing internals.
```

Do not introduce a new router before the current behavior is fully covered by tests.

---

# 21. Related documents

Read together with:

- `architecture.md`
- `core.md`
- `database.md`
- `technical-debt.md`

---

# 22. Status

This document describes the confirmed routing mechanism of the Premium Avto legacy system.

It should be updated only after verifying new routing behavior in source code.
