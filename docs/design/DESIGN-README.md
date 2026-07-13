# Premium Avto Design System

This directory contains the official design system for the Premium Avto project.

Unlike the architecture documentation, these files describe the visual product rather than the software implementation.

## Structure

assets/
    Approved visual references.

prompts/
    AI generation prompts used to create the approved design assets.

DS-xxx
    Design System specifications.

## Current approved assets

- hero-background-v1.png
- hero-concept-v1.png

These images are the official visual references for the Hero section and should be used as the basis for all future implementation.

They are architectural design artifacts rather than temporary mockups.

## Approved Components

- DS-001 — Hero Specification
- DS-002 — Trust Block Specification
- DS-003 — Gallery Specification
- H-006 — Services Section

Implemented:

- Hero
- Header
- Brand Strip
- Trust Block
- Services Section

Current Design Task:

- H-007 — Gallery Modernization
---

# Implementation Notes

The approved design assets are not only artistic references but also implementation references.

In particular:

- `hero-concept-v1.png` defines the overall composition of the first screen.
- The implementation should remain visually consistent with the approved concept whenever technically possible.
- Desktop and mobile layouts may differ, but both should preserve the same visual hierarchy and product message.
- Mobile adaptations may adjust typography, spacing and alignment while preserving the approved design intent.
- The Services Section continues the visual language established by the Hero and Trust Block.
- Service data and prices remain managed by the existing block2 CMS module.
- Desktop uses a compact two-column service grid.
- Mobile uses a single-column responsive layout.
- Existing service images are reused as compact supporting thumbnails.
- The legacy public “Why us?” block has been removed from the homepage because its communication role is now fulfilled by the Trust Block.
