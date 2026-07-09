# Premium Avto — Project Principles

> **Document:** Project Principles
>
> **Version:** 1.0
>
> **Status:** Active

---


# Основная цель проекта

Не переписывать legacy-систему с нуля.

Сохранять её работоспособной и постепенно улучшать архитектуру.

---

# Основные принципы

## 1. Preserve First

Любое изменение должно сохранять существующий функционал.

---

## 2. Small Steps

Большие изменения разбиваются на небольшие независимые этапы.

---

## 3. One Commit — One Task

Каждый commit должен содержать одну логически завершённую задачу.

---

## 4. Always Working

После каждого commit проект должен полностью запускаться.

---

## 5. Rollback Ready

Любое изменение должно легко откатываться через Git.

---

## 6. Documentation First

Архитектурные изменения сначала описываются в документации, затем реализуются в коде.

---

## 7. No Blind Refactoring

Нельзя изменять код, если не понятна его роль.

Сначала исследование — потом изменение.

---

## 8. Test Every Change

После каждого изменения обязательно пройти `testing-checklist.md`.

---

## 9. Minimize Risk

Предпочтение отдаётся решениям с минимальным риском для существующей системы.

---

## 10. Improve Continuously

Каждый этап должен делать проект немного лучше предыдущего.

## 11. Product-first principle

Once the platform is stable, priority should shift from internal refactoring to improvements that provide visible value to users and administrators.

Technical cleanup remains important but should support product evolution rather than become a goal by itself.
---
## 12. Legacy is the foundation, not the destination

Understanding the legacy code is required only to modernize it safely.

The final objective is a modern website, not a perfectly documented legacy system.

---

# Что допускается

- рефакторинг небольшими шагами;
- повышение безопасности;
- улучшение совместимости с PHP 8;
- улучшение читаемости кода;
- добавление документации;
- постепенное обновление интерфейса.

---

# Что запрещено

- массовое переписывание работающего кода;
- изменение нескольких подсистем в одном commit;
- удаление функционала без анализа;
- изменение архитектуры без документирования;
- commit без проверки проекта.

---

# Рабочий цикл

```text
Исследование

↓

Документация

↓

Изменение

↓

Проверка

↓

Commit

↓

Push
```

---

# Цель

К окончанию проекта Premium Avto должен:

- работать на современных версиях PHP;
- иметь актуальную документацию;
- легко сопровождаться новым разработчиком;
- сохранять совместимость с существующим функционалом;
- иметь понятную архитектуру и минимальный технический долг.

---

# История изменений

| Версия | Дата | Изменение |
|--------|------|-----------|
| 1.0 | 2026-07 | Создание документа |