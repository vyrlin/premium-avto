# Changes for start in Laragon

## Purpose

This document describes all temporary and permanent changes that were required to launch the legacy Premium-Avto website locally in Laragon under PHP 8.1.

This document should be reviewed before deploying the project back to production.

---

# Local environment

Environment:

- Windows 11
- Laragon
- Apache 2.4
- PHP 8.1
- MySQL 8.0

Virtual Host

DocumentRoot:

public_html/

---

# PHP configuration

Enabled

short_open_tag = On

Reason:

The legacy project uses many short PHP tags:

<?
<?=

Without this option PHP outputs source code instead of executing it.

---

# Apache

Apache VirtualHost contains

AllowOverride All

DocumentRoot points to

public_html

PHP processing enabled for html files.

---

# .htaccess

During debugging RewriteRules were temporarily disabled.

After successful launch they were restored.

Final .htaccess corresponds to the original version.

---

# Database

Original production settings

DB_USER = detalauto_pa

DB_PASSWORD = ********

Temporary local settings

DB_USER = root

DB_PASSWORD =

Database name

detalauto_pa

The database was imported into local MySQL.

Before deployment restore production credentials.

---

# PHP 8 compatibility

## auth.php

Old PHP syntax

$text{$i}

was replaced by

$text[$i]

Affected functions

xorhash()

dexorhash()

Reason

PHP 8 removed support for curly brace string offsets.

---

## classes.php

Magic methods

__wakeup()

were changed from

private

to

public

Reason

PHP 8 requires public visibility.

---

# Successfully launched

Homepage

Admin panel

Database connection

Images

Routing

Templates

All work correctly.

---

# Local Mail Testing with Mailpit

- Mailpit is used to capture outgoing mail safely during local development.
- The Mailpit UI is available at `http://127.0.0.1:8025`.
- PHP `mail()` is directed to Mailpit through the local Laragon configuration.
- Local `php.ini` and Laragon `Procfile` settings are environment-specific and are not stored in this repository.
- Real SMTP is not used during local development.
- Mailpit must be configured again after moving the project to another computer.
- Production mail configuration and real delivery must be verified separately after deployment.

---

# Before production deployment

Restore production database credentials.

Review PHP 8 compatibility changes.

Compare local config.php with production config.php.

Do NOT overwrite production credentials accidentally.
