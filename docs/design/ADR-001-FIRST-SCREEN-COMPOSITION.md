# ADR-001 — First Screen Composition

> **Document:** ADR-001 — First Screen Composition
>
> **Version:** 1.0
>
> **Status:** Approved
>
> **Project Phase:** Product Modernization

---

# 1. Context

During implementation of the modern Hero section (H-001–H-004), multiple design iterations demonstrated that treating the Hero, supporting components and adjacent sections as independent blocks resulted in weaker visual communication.

The project goal is not to reproduce a traditional corporate website.

The goal is to communicate professionalism, trust and specialization within the first few seconds of a visitor's interaction.

This requires the first screen to function as one coherent visual composition.

---

# 2. Decision

The first screen of Premium Avto shall be treated as a single visual product rather than a collection of independent components.

The following elements together form the first screen:

- Header
- Hero Background
- Hero Content
- CTA
- Key Advantages
- Brand Strip

These components are designed together and must be visually balanced as one composition.

No individual component should dominate the first screen at the expense of the overall hierarchy.

---

# 3. Design Hierarchy

The visual hierarchy of the first screen is defined as follows.

Priority 1

Hero headline.

The visitor must immediately understand:

> Independent specialized VAG service.

Priority 2

Background image.

The background establishes trust and professionalism.

Priority 3

Primary CTA.

The next obvious action should be booking a service.

Priority 4

Supporting information.

This includes:

- Hero description
- Key advantages
- Brand Strip

These elements reinforce trust without competing with the primary message.

---

# 4. Responsive Philosophy

Desktop and mobile layouts may differ significantly.

However, both layouts must preserve the same communication hierarchy.

Responsive design is not considered a simplified copy of the desktop layout.

Instead, each layout should be optimized for its own screen size while preserving the approved design intent.

---

# 5. Approved Reference

The official visual reference for the first screen is:

- `docs/design/assets/hero-concept-v1.png`

Whenever implementation differs from the approved concept, the implementation should be adjusted unless technical constraints make this impossible.

---

# 6. Future Development

All future modifications affecting:

- Header
- Hero
- CTA
- Brand Strip
- Key Advantages

must be evaluated against this ADR.

Changes that improve an individual component while weakening the first-screen composition should not be accepted.

---

# 7. Consequences

This decision establishes a product-oriented design philosophy.

Future development should optimize the visitor's first impression rather than individual UI components.

The first screen is considered a complete communication unit.

Its effectiveness is evaluated as a whole.

---

# Status

Approved.

This ADR becomes part of the official Design System of Premium Avto.