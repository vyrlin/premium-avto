# Premium Avto — Security Audit

> **Status:** Active
>
> **Project Phase:** Phase 3 — Security & Production Release
>
> **Last Updated:** 2026-07-13
>
> **Purpose:** Maintain the authoritative security assessment and modernization priorities for the Premium Avto project.

---

# 1. Executive Summary

The current overall security risk is **Critical**.

This is a living project document and will be expanded as subsequent Security Audit stages are completed. Sections marked **Pending** indicate only that their dedicated audit has not yet been completed; they do not indicate that the area is free of security risk.

The primary reason is a confirmed unauthenticated Local File Inclusion / Path Traversal vulnerability in the public routing flow. User-controlled route data reaches a local `include()` path without route allowlisting, canonical path containment or extension restrictions.

The vulnerability can cause execution of an existing local PHP file when that file is reachable through the vulnerable include path. This does not automatically constitute confirmed Remote Code Execution. Full RCE would require an additional controlled local file or another suitable attack chain.

The authentication audit also confirmed a hardcoded password bypass, legacy MD5 password hashing, unsafe persistent-cookie authentication, missing session ID regeneration and absent brute-force protection.

The input-handling audit confirmed multiple administrator-only SQL Injection paths, public and administrative Stored XSS, and a Reflected XSS path in a legacy administrative module. These findings are individually rated High where administrator privileges are normally required. The authentication bypass materially increases their practical exploitability.

No security fixes were implemented during S-001A or S-001B. Both tasks were audit-only activities.

## Confirmed positive controls

- Appointment accepts POST only and validates required fields as strings.
- Appointment validates email format and rejects CR/LF characters.
- Appointment applies explicit field limits and encodes HTML email values.
- Appointment responses use `json_encode()`, and the client uses `textContent`.
- AJAX route characters are restricted, and `admin` AJAX paths require administrator authorization.
- Gallery alternative text is stripped and HTML-encoded.
- Common text, email, date, time and hidden inputs encode attribute values.
- Numeric route identifiers pass through a digits-only filter.
- Password migration helpers based on `password_hash()` and `password_verify()` already exist, although the current login flow does not use them.

---

# 2. Overall Risk Assessment

**Overall Risk: Critical**  
**Primary reason: confirmed unauthenticated Local File Inclusion / Path Traversal.**

## Risk context

| Area | Highest confirmed severity | Access requirement | Assessment |
|---|---|---|---|
| Public routing | Critical | None | Confirmed LFI / Path Traversal |
| Authentication | Critical | Public login access | Confirmed hardcoded password bypass |
| Password storage | High | Database disclosure required for offline attack | Confirmed legacy MD5 hashing |
| Persistent authentication | High | Possession or construction of cookie material | Confirmed insufficient validation |
| Administrative SQL | High | Administrator under normal conditions | Multiple confirmed SQL Injection paths |
| Public CMS rendering | High | Administrator write access or poisoned database | Confirmed Stored XSS |
| Administrative rendering | High | CMS/database write access | Confirmed Stored XSS and Reflected XSS |
| Input validation architecture | Medium | Varies | Confirmed context conflation and missing schema validation |

## Audit status

| Audit Area | Status |
|---|---|
| Authentication | Completed |
| Input Validation | Completed |
| SQL Injection | Completed |
| XSS | Completed |
| CSRF | Pending |
| AJAX Security | Pending |
| File Upload | Pending |
| Access Control | Pending |
| HTTPS | Pending |
| HTTP Security Headers | Pending |
| PHP Configuration | Pending |
| Error Handling | Pending |

## Critical routing finding

The public route parameter flows through `_core/_parser/path.php` into `$_PATH['path_string']`. `PAGE::getContent()` then concatenates it with `$_CORE_ROOT`, checks only whether the resulting file exists and passes it to `include()`.

Existing checks do not establish that the normalized path remains in an approved directory. `realpath()` containment and a route allowlist are absent. `file_exists()` confirms existence but does not authorize access.

Safe read-only verification confirmed that repeated query parameters resolve to the final supplied value, that this value becomes `path_string`, and that the generated scheme can address existing text and PHP files. No target file content was read or included during verification.

## Severity dependencies

- Without the routing finding, the overall standalone risk would be **High**.
- Without the S-001A authentication bypass, the overall risk remains **Critical** because the routing vulnerability is unauthenticated.
- S-001A increases the likelihood of all administrator-only SQL Injection and XSS findings.
- Several High findings can produce Critical practical impact only when combined into an attack chain.

## Trust boundaries

```text
Browser
↓
PHP entry point
↓
Router / Forms / AJAX
↓
SQL / Filesystem / Email
↓
Database
↓
PHP rendering
↓
HTML / JavaScript
↓
Browser
```

| Boundary | Main sources | Main sinks | Primary risk |
|---|---|---|---|
| Browser → PHP | GET, POST, COOKIE, upload metadata | Superglobals, Form framework | Missing schema and scalar validation |
| PHP → Filesystem | Route parameter | `include()` | LFI / Path Traversal |
| PHP → SQL | Form, session and route values | `DB::query()`, `select()`, `selectOne()` | Raw SQL concatenation |
| PHP → Email | Appointment POST fields | HTML email body | Current endpoint applies controlled validation and encoding |
| Database → PHP | `texts`, `gallery`, `seo`, `users` | DB result arrays | Database values treated as trusted |
| PHP → Browser | CMS, forms, modal and SEO renderers | Body, attributes, textarea, meta, inline JavaScript | Missing contextual output encoding |

---

# 3. Authentication Security

**Audit:** S-001A — Authentication Security  
**Status:** Completed; remediation pending

## Authentication flow

```text
POST login
↓
email and password filtering
↓
users table lookup
↓
legacy password verification
↓
active status check
↓
session and persistent cookie creation
↓
administrator redirect
```

## Confirmed findings

| ID | Finding | Severity | Status |
|---|---|---|---|
| AUTH-001 | Hardcoded password bypass | Critical | Confirmed |
| AUTH-002 | Legacy MD5 password hashing | High | Confirmed |
| AUTH-003 | Unsafe persistent authentication cookie | High | Confirmed |
| AUTH-004 | Insufficient persistent-cookie validation | High | Confirmed |
| AUTH-005 | Session ID regeneration absent after login | High | Confirmed |
| AUTH-006 | Brute-force protection absent | High | Confirmed |
| AUTH-007 | Role and active state can remain trusted from session data | High | Confirmed |
| AUTH-008 | Authentication queries use raw SQL construction | High | Confirmed architectural risk |
| AUTH-009 | Logout accepts state-changing request without confirmed CSRF protection | Medium | Confirmed |
| AUTH-010 | Complete server-side session destruction is absent | Medium | Confirmed |
| AUTH-011 | Token generation uses non-cryptographic legacy randomness | Medium | Confirmed |

## Key conclusions

- The hardcoded bypass must be removed before production release.
- MD5 password records require gradual migration after successful login; they cannot be securely converted without the original password.
- Existing modern password helper functions reduce migration cost but require integration and regression testing.
- Authentication SQL should be the first database flow migrated to prepared statements.
- Role and active status should be refreshed from authoritative server-side state for sensitive administrative requests.

Migration to `password_hash()` should occur transparently after a successful legacy-password authentication. A mass password reset is not recommended unless a separate incident-response requirement makes it necessary.

---

# 4. Session & Cookie Security

**Status:** Audited as part of S-001A; remediation pending

## Confirmed weaknesses

- `session_regenerate_id(true)` is not called after successful authentication.
- Persistent authentication restores a user from an identifier while parsed cookie components are not all cryptographically validated.
- Persistent tokens are not implemented as revocable random server-side tokens.
- Cookie creation does not consistently set `Secure` and `SameSite` attributes.
- IP and User-Agent binding are used as legacy signals but do not replace a cryptographic token signature.
- Logout clears values and cookies but does not perform a complete confirmed `session_destroy()` flow.
- Logout is reachable through a state-changing request without confirmed CSRF protection.

## Required direction

- Regenerate the session ID after login and privilege changes.
- Store only a user identifier and minimal state in the session.
- Replace the custom persistent cookie with a random, revocable, hashed server-side token.
- Apply `Secure`, `HttpOnly` and an appropriate `SameSite` policy in production.
- Invalidate persistent tokens and destroy server-side session state during logout.

---

# 5. Input Validation

**Audit:** S-001B — Input Validation, SQL Injection & XSS Security Audit  
**Status:** Completed; remediation pending

## Current validation architecture

`secur()` performs several unrelated responsibilities:

- normalization;
- character allowlisting;
- SQL escaping;
- HTML encoding;
- numeric and hash filtering;
- password-character filtering.

This makes the security contract of a processed value ambiguous. A value safe in one context may remain unsafe in another.

## Function assessment

### `secur()`

| Mode | Current purpose | Assessment |
|---|---|---|
| `tr` | Latin/Cyrillic text allowlist | Not safe for SQL; destructive for wider Unicode |
| `te` | Restricted Latin text allowlist | Not safe for SQL |
| `t` | `mysqli_real_escape_string()` | SQL-context only; not HTML-safe |
| `form` | Tag removal and HTML encoding | Useful for simple attributes but mixes sanitization and encoding |
| `i` | Digits only | Effective for current numeric route identifiers |
| `none` | Whitespace trimming only | Unsafe for direct SQL or HTML use |
| `email` | Character removal | Not equivalent to email validation |
| `hash` | Hexadecimal allowlist | Suitable only for strict hexadecimal identifiers |

Additional weaknesses:

- arrays are recursively accepted where scalar values may be required;
- array keys are not validated;
- object and invalid type handling is not explicit;
- loose `NULL` comparisons are used;
- byte-oriented truncation can occur before Unicode processing;
- invalid UTF-8 handling is not explicit;
- valid international input may be silently damaged.

### `my_strip_tags()`

`my_strip_tags()` is a regular-expression and keyword-blacklist filter. It is not a trusted HTML sanitizer because it does not parse browser HTML, define a tag/attribute allowlist, validate URL protocols or account for output context.

### `DB::secure()`

`DB::secure()` wraps `mysqli_real_escape_string()` but is not used consistently. Even universal use would not replace prepared statements or contextual output encoding.

## Confirmed findings

| ID | Finding | Severity | Confidence |
|---|---|---|---|
| IV-001 | Validation, sanitization, SQL escaping and HTML encoding are conflated | Medium | High |
| IV-002 | Scalar, array, `NULL`, size and Unicode validation are inconsistent | Medium | High |

## Future direction

Separate:

1. request schema validation;
2. normalization;
3. prepared SQL binding;
4. contextual output encoding;
5. trusted rich-HTML sanitization;
6. file and URL validation.

---

# 6. SQL Injection

**Status:** Multiple confirmed High findings; remediation pending

## Systemic assessment

- Application queries do not use prepared statements.
- `DB::query()`, `select()` and `selectOne()` accept complete SQL strings.
- Most writes use string concatenation.
- Character allowlists are incorrectly used as a substitute for parameterized SQL queries.
- The verified local SQL mode permits backslash interpretation inside string literals.
- Administrator access is normally required for the confirmed CMS paths.
- The S-001A authentication bypass materially increases practical likelihood.

## Confirmed paths

| ID | Path | Source | Severity | Confidence |
|---|---|---|---|---|
| SQL-001 | block1, block4, block5 and legacy block create/update | Adjacent CMS fields using `tr` and `t` | High | High |
| SQL-002 | SEO update | Consecutive SEO fields using `tr` | High | High |
| SQL-003 | block2 and block6 create | `text` using `none` | High | High |
| SQL-004 | block2 and block6 update | Adjacent title and text fields | High | High |
| SQL-005 | Gallery create | Raw `image_hidd` POST value | High | High |

Representative statements for block1, SEO, block2, block6 and Gallery were accepted through server-side `PREPARE` without `EXECUTE`. No data-changing statement was executed.

## Why existing protections fail

- `tr` and `te` are input allowlists, not SQL encoders.
- Their permitted character set can affect MySQL literal parsing in the verified SQL mode.
- Escaping an adjacent value does not restore a query structure already changed by a preceding value.
- `none` performs no SQL escaping.
- Gallery discards the processed hidden-field value and reads raw `$_POST` again.
- Hidden browser fields are not trusted server-side state.

## Conditional and rejected paths

| Path | Status | Reason |
|---|---|---|
| `admin/users/index.html` | Conditional | Unsafe construction exists, but the current schema rejects the normal query because an expected column is absent |
| Gallery update through description | Not confirmed | The confirmed adjacent-field structure is absent |
| Numeric route identifiers | Rejected | Current identifiers are reduced to digits |
| Appointment | Rejected | The endpoint issues no SQL queries |

## Remediation order

1. block2 and block6 create;
2. Gallery hidden-field path;
3. block1, block4, block5 and legacy block;
4. SEO;
5. block2 and block6 update;
6. users after schema reconciliation;
7. remaining reads and authentication queries.

---

# 7. XSS

**Status:** Confirmed Stored and Reflected XSS; no confirmed public DOM XSS

## Stored XSS

| ID | Source | Storage | Rendering context | Encoding | Status | Severity | Confidence |
|---|---|---|---|---|---|---|---|
| XSS-001 | block2 text | `texts` | Public HTML body | None for detected HTML | Confirmed | High | High |
| XSS-001 | block4 CKEditor text | `texts` | Public HTML body | None | Confirmed | High | High |
| XSS-002 | CKEditor block data | `texts` | Administrative textarea | None | Confirmed | High | High |
| XSS-002 | block2/block6 textarea data | `texts` | Administrative textarea | None | Confirmed | High | High |
| XSS-C01 | Gallery values | `gallery` | Modal body and URL attribute | Incomplete | Conditional on poisoned database | Medium | Medium |
| XSS-C02 | SEO values | `seo` | Title and meta attribute | None | Conditional on SQLi or poisoned database | High in chain | High |

### Stored HTML Injection and trusted HTML

Block2 and block4 intentionally support CMS formatting. HTML support is not automatically a vulnerability. The confirmed security issue is that administrative input can create browser-executable content without a trusted server-side allowlist sanitizer.

Static template fragments are considered trusted. CMS rich HTML should be treated as trusted only after authorization, server-side sanitization and URL protocol validation.

Even trusted administrative input must not automatically be treated as safe HTML. The server must enforce an explicit trusted-HTML policy and apply safe server-side sanitization before stored content reaches a browser rendering context.

## Reflected XSS

One complete path is confirmed in `admin/block/index.html`:

```text
Current administrative POST
↓
CKEditor field
↓
SQL-only escaping
↓
constructed SQL string
↓
raw diagnostic output
↓
administrator browser
```

| ID | Finding | Status | Severity | Confidence |
|---|---|---|---|---|
| XSS-003 | Reflected XSS through legacy SQL diagnostic output | Confirmed | High | High |

No public Reflected XSS was confirmed in Appointment or signin.

## DOM XSS

| Sink | Source | Attacker control | Status |
|---|---|---|---|
| `inputFile()` → `innerHTML` | Browser file value | Browser/filesystem restricted | Conditional / Low |
| `showModal()` → `innerHTML` | Function parameters | No confirmed external source | Not confirmed |
| `showModals()` → `innerHTML` | Optional parameters | No confirmed current attacker-controlled call | Not confirmed |
| Appointment status | Controlled JSON response | Rendered with `textContent` | Rejected |

No confirmed path from `location`, `hash`, `search`, AJAX data or data attributes to an exploitable DOM HTML sink was found.

## Rejected XSS findings

- public Reflected XSS in Appointment;
- Reflected XSS in signin;
- direct Stored XSS through the normal Gallery description field;
- public DOM XSS in the current application call graph;
- Stored XSS through Gallery alternative text.

---

# 8. CSRF

**Status:** Pending dedicated audit.

Authentication review identified logout CSRF exposure. A complete review of administrative state-changing actions remains pending.

---

# 9. AJAX Security

**Status:** Pending dedicated audit.

S-001B confirmed positive controls in the current Appointment endpoint and AJAX loader. Broader endpoint authorization and CSRF assessment remains pending.

---

# 10. File Upload Security

**Status:** Pending dedicated audit.

---

# 11. Administrative Area

**Status:** Pending dedicated audit.

S-001A and S-001B findings affecting administrator authentication, SQL handling and browser rendering are recorded above.

---

# 12. Access Control

**Status:** Pending dedicated audit.

S-001A confirmed that authorization depends on legacy session and cookie restoration. A complete module-by-module access-control matrix remains pending.

---

# 13. Configuration & Secrets

**Status:** Pending dedicated audit.

---

# 14. PHP Configuration

**Status:** Pending dedicated audit.

---

# 15. .htaccess Review

**Status:** Pending complete review.

The confirmed routing vulnerability is documented in the Overall Risk Assessment. A broader rewrite, access-control and production-hardening review remains pending.

---

# 16. HTTPS

**Status:** Pending dedicated audit.

Production HTTPS is required before enabling `Secure` authentication cookies.

---

# 17. HTTP Security Headers

**Status:** Pending dedicated audit.

---

# 18. Error Handling

**Status:** Pending dedicated audit.

---

# 19. Legacy Risks

**Status:** Pending consolidated review.

Confirmed legacy risks currently include the authentication bypass, MD5 password hashing, custom persistent cookies, raw SQL construction, regex-based sanitization and unsafe CMS output contexts.

---

# 20. Production Readiness

**Status:** Not ready for production security approval.

Production approval is blocked by:

- unauthenticated LFI / Path Traversal;
- hardcoded authentication bypass;
- legacy password storage;
- unsafe persistent authentication;
- confirmed administrative SQL Injection;
- confirmed public and administrative XSS.

The project must not be considered production-ready until all Critical risks have been remediated and the remaining High risks have been reassessed in accordance with the project's security policy.

---

# 21. Security Modernization Roadmap

Security work must remain incremental, regression-tested and isolated into one logical commit per remediation step.

| Priority | Work item | Security urgency | Business logic impact | Complexity | Commit isolation |
|---:|---|---|---|---|---|
| 1 | Close LFI / Path Traversal | Immediate | Medium | Medium | Dedicated routing-security commit |
| 2 | Remove authentication bypass | Immediate | Low–Medium | Low | Dedicated authentication hotfix |
| 3 | Migrate authentication lookup to prepared SQL | Immediate | Low | Medium | Dedicated authentication SQL commit |
| 4 | Add session regeneration and secure cookie policy | High | Low–Medium | Low–Medium | Dedicated session hardening commit |
| 5 | Replace persistent-cookie authentication | High | Medium–High | Medium–High | Dedicated token migration commit |
| 6 | Add brute-force protection | High | Low–Medium | Medium | Dedicated login protection commit |
| 7 | Fix confirmed CMS SQL Injection | Immediate | Low–Medium | Medium–High | Separate commits by module group |
| 8 | Sanitize public CMS rich HTML | High | Medium | Medium | Dedicated CMS HTML policy commit |
| 9 | Encode administrative textarea values | High | Very Low | Low | Dedicated output-encoding commit |
| 10 | Remove legacy reflected SQL output | High | None | Very Low | Dedicated legacy cleanup commit |
| 11 | Migrate MD5 passwords gradually | High | Medium | Medium | Dedicated password migration commit |
| 12 | Complete prepared-statements migration | High | Medium | High | Incremental module commits |
| 13 | Introduce contextual output encoding | High | Low–Medium | Medium | Separate public/admin/JavaScript commits |
| 14 | Redesign input validation boundaries | Medium–High | Medium | High | Architecture commit plus incremental migrations |

## Required remediation sequence

1. Remove public unauthenticated entry points to critical compromise.
2. Restore authentication integrity.
3. Eliminate confirmed SQL Injection paths.
4. Establish a trusted CMS HTML policy.
5. Apply contextual output encoding.
6. Migrate legacy password and persistent-token formats.
7. Complete the remaining dedicated audit sections.

---

# 22. Audit Scope & Limitations

This audit is based on the project source code examined during the completed security review stages and on verification performed in the project's local development environment.

The assessment included:

- static source-code analysis;
- safe read-only verification;
- review of confirmed source-to-sink data flows;
- non-destructive validation of selected runtime behavior.

The following activities and environments were outside the scope of the completed audit:

- destructive testing;
- penetration testing;
- server infrastructure analysis;
- operating-system configuration analysis;
- production-hosting configuration analysis.

Future source-code, dependency, configuration or infrastructure changes may introduce risks outside the scope of this audit. The document must therefore continue to be reviewed and extended as the project and its production environment evolve.

---

**Document status:** S-001A and S-001B integrated. Remaining security sections are intentionally marked Pending until their dedicated audits are completed.
