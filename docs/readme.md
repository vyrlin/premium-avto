# Premium Avto Legacy Technical Reference Manual

> **Version:** 1.0\
> **Status:** Active\
> **Purpose:** Technical documentation of the legacy system before
> modernization.

------------------------------------------------------------------------

# About this documentation

This documentation was created during a reverse engineering process
after successfully launching the legacy site locally.

Its goal is **not to describe every file**, but to help a new developer
understand the architecture, locate the required functionality, and
safely modify the project.

Every statement in these documents is based on the source code that has
already been analyzed.

------------------------------------------------------------------------

# Documentation map

``` text
docs/

README.md                        ← Start here

architecture.md                  Overall architecture
core.md                          Core (_core)
routes.md                        Request lifecycle and routing
database.md                      Database usage
technical-debt.md                Legacy issues and modernization plan

oldversion.md                    Historical notes
changes-for-start-in-laragon.md  Local environment
roadmap.md                       Modernization roadmap
```

------------------------------------------------------------------------

# Recommended reading order

1.  README.md
2.  architecture.md
3.  core.md
4.  routes.md
5.  database.md
6.  technical-debt.md

------------------------------------------------------------------------

# System overview

``` text
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
$_PATH
   │
   ▼
PAGE::getContent()
   │
   ▼
include(page)
   │
   ▼
PAGE::html()
   │
   ▼
landing.html / cabinet.html
   │
   ▼
DB::select(...)
   │
   ▼
MySQL
```

------------------------------------------------------------------------

# Main project components

  Component     Purpose
  ------------- --------------------------
  public_html   Public entry point
  \_core        Core framework
  admin         Administrative interface
  \_ajax        AJAX endpoints
  signin        Authentication

------------------------------------------------------------------------

# Documentation principles

-   Only verified facts.
-   No assumptions.
-   Documentation describes the system, not only files.
-   Documentation is updated together with the code.

------------------------------------------------------------------------

# Development principles

-   Keep the legacy site working after every change.
-   Small commits.
-   One logical change per commit.
-   Ability to rollback every stage.

------------------------------------------------------------------------

# Milestone

Current state:

-   Legacy site runs locally.
-   Core architecture investigated.
-   Routing understood.
-   Main classes documented.
-   Safe modernization can begin after documentation is completed.
