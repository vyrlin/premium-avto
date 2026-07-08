# Premium Avto — Testing Checklist

> **Document:** Testing Checklist
>
> **Version:** 1.0
>
> **Status:** Active
>
> **Purpose:** Define the mandatory verification steps after every code modification.

---

# 1. Goal

Every commit must leave the project in a fully working state.

Testing is mandatory after any modification, regardless of how small the change is.

---

# 2. Smoke Test (after every commit)

Perform these quick checks after each commit.

## Public Website

- [ ] Homepage opens successfully.
- [ ] No PHP errors are displayed.
- [ ] No JavaScript errors appear in the browser console.
- [ ] CSS is loaded correctly.
- [ ] Images are displayed.
- [ ] Navigation menu works.

---

## Content

Verify that all landing page blocks are displayed.

- [ ] Block 1 — Почему мы?
- [ ] Block 2 — Услуги
- [ ] Block 3 — Фотогалерея
- [ ] Block 4 — Контакты
- [ ] Block 5 — О компании
- [ ] Block 6 — Партнёры

---

## Gallery

- [ ] Gallery images are displayed.
- [ ] Gallery layout is correct.

---

## SEO

- [ ] Page title is correct.
- [ ] Meta description is present.
- [ ] No broken HTML markup.

---

## Database

- [ ] Database connection succeeds.
- [ ] Content is loaded from the database.

---

## Administration

- [ ] Admin login page opens.
- [ ] Login works.
- [ ] Admin menu is displayed.
- [ ] Text blocks open correctly.
- [ ] Gallery management opens.
- [ ] SEO editor opens.

---

## PHP

- [ ] No Fatal Errors.
- [ ] No Warnings.
- [ ] No Notices (unless already documented).
- [ ] No Deprecated messages introduced.

---

# 3. Regression Test (before release)

Run the full checklist before merging significant changes.

## Routing

- [ ] Homepage.
- [ ] Admin pages.
- [ ] Authentication.
- [ ] Invalid URL handling.

---

## Templates

- [ ] landing.html
- [ ] cabinet.html
- [ ] print.html

render correctly.

---

## Forms

- [ ] Login form.
- [ ] Content editing.
- [ ] Gallery editing.
- [ ] SEO editing.

---

## Database

Verify:

- [ ] texts
- [ ] gallery
- [ ] seo
- [ ] users

---

## Browser Console

- [ ] No JavaScript errors.
- [ ] No missing resources (404).

---

## Performance

- [ ] Homepage loads successfully.
- [ ] Images load correctly.
- [ ] CSS and JS files load successfully.

---

# 4. After Documentation Changes

If only documentation changes:

- Functional testing is not required.
- Verify Markdown formatting.

---

# 5. After UI Changes

Verify additionally:

- Desktop layout.
- Mobile layout.
- Navigation.
- Images.
- Typography.

---

# 6. After PHP Changes

Mandatory checks:

- Homepage
- Admin login
- Database
- Content blocks
- Gallery
- SEO

---

# 7. Commit Rule

A commit is considered complete only if:

- Documentation is updated (when required).
- Tests have been performed.
- Git working tree is clean.
- Project remains fully operational.

---

# 8. Testing Log

For significant changes, record:

- Date
- Commit hash
- Tested by
- Result
- Notes

---

# Status

This checklist is mandatory for all future development of the Premium Avto project.