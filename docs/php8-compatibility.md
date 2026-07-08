# PHP 8 Compatibility

## Purpose

This document contains all source code changes required to run the legacy Premium-Avto project under PHP 8.x.

The goal is to separate **compatibility fixes** from **functional changes**.

Only PHP-version related modifications should be documented here.

---

# Environment

Legacy project:
- PHP 5.x

Current development:
- PHP 8.1
- Apache 2.4
- MySQL 8

---

# Compatibility Changes

---

## 1. Enable short_open_tag

### Problem

The project uses hundreds of short PHP tags:

```php
<?
```

PHP outputs source code instead of executing it when `short_open_tag=Off`.

### Solution

php.ini

```ini
short_open_tag=On
```

Status

✅ Applied

---

## 2. String offset syntax

### Problem

PHP 8 removed support for

```php
$string{$i}
```

Fatal error:

```
Array and string offset access syntax with curly braces is no longer supported
```

### File

```
_core/_functions/auth.php
```

### Old

```php
$hash .= $text{$i} ^ $salt{$j};
```

```php
$text .= $hash{$i} ^ $salt{$j};
```

### New

```php
$hash .= $text[$i] ^ $salt[$j];
```

```php
$text .= $hash[$i] ^ $salt[$j];
```

Status

✅ Applied

---

## 3. Magic methods visibility

### Problem

PHP 8 requires all magic methods to be public.

Warning

```
__wakeup() must have public visibility
```

### File

```
_core/_classes/classes.php
```

### Old

```php
private function __wakeup()
```

or

```php
protected function __wakeup()
```

### New

```php
public function __wakeup()
```

Status

✅ Applied

---

# Database

No PHP compatibility changes.

Only local development credentials were used.

Production credentials must be restored before deployment.

---

# Known Future Issues

The following deprecated PHP functionality may still exist inside the project.

## mysql_* functions

Need verification.

Status

☐ Not checked

---

## create_function()

Need verification.

Status

☐ Not checked

---

## each()

Need verification.

Status

☐ Not checked

---

## split()

Need verification.

Status

☐ Not checked

---

## preg_replace() with /e

Need verification.

Status

☐ Not checked

---

## Dynamic properties

PHP 8.2+

Need verification.

Status

☐ Not checked

---

## utf8 / mbstring

Need verification.

Status

☐ Not checked

---

# Migration Log

| Date | PHP Version | Change | Status |
|------|-------------|--------|--------|
| 2026-07-08 | PHP 8.1 | Enabled short_open_tag | ✅ |
| 2026-07-08 | PHP 8.1 | Replaced string offset {} → [] | ✅ |
| 2026-07-08 | PHP 8.1 | Changed __wakeup() visibility to public | ✅ |

---

# Notes

Only compatibility fixes should be added to this document.

Business logic changes belong to the project documentation, not here.