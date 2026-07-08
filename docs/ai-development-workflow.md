# Premium Avto — AI Development Workflow

> **Document:** AI Development Workflow
>
> **Version:** 1.0
>
> **Status:** Active
>
> **Purpose:** Define the standard workflow for collaborative development between the developer and AI during the modernization of the Premium Avto project.

---

# 1. Goal

The purpose of this document is to establish a predictable, repeatable and safe development process.

The workflow is designed to:

- reduce mistakes;
- minimize misunderstandings;
- make every change traceable;
- keep documentation synchronized with the code;
- allow any new developer to continue the project with minimal onboarding.

---

# 2. General Principles

The project follows these rules:

- Never rewrite working code without analysis.
- Small changes are preferred over large refactoring.
- One commit = one logical task.
- Every commit must leave the project in a working state.
- Documentation is updated together with the code.
- Every decision should be documented.

---

# 3. Standard Task Format

Every task proposed by AI must use the following structure.

```text
📌 Task #XXX

🎯 Goal

📄 Files

💻 Commands

📝 Changes

🧪 Testing

📦 Commit
```

This format must be used consistently throughout the project.

---

# 4. File Creation Rule

Whenever AI proposes creating a new file, it must always provide:

1. the file path;
2. the PowerShell command to create it.

Example:

```powershell
ni .\docs\example.md -ItemType File
```

The developer should never have to ask how to create the file.

---

# 5. File Modification Rule

Whenever AI proposes changing an existing file, it must explicitly specify one of two options.

## Option A — Replace the entire file

AI provides the complete new version of the file.

No partial fragments.

---

## Option B — Replace one section

AI specifies:

- the section name;
- the complete replacement text.

The replacement must always be provided as one copyable Markdown block.

Example:

````markdown
## PAGE

...