# Mini Blog – Internal Architecture

## Overview

This project is a small blog application built to practice the MVC architectural pattern in plain PHP.


# Project Structure & MVC Layout:


The main purpose of this project is to learn the MVC architectural pattern by gradually building a small blog application.  
Architectural patterns like MVC exist primarily to organize code, separate responsibilities, and make changes easier over time.

This document explains how the project is structured into folders and what role each part plays.

---

## 1. Front Controller (`public/index.php`)

The entry point of the entire application is `public/index.php`.

It is responsible for:

- Receiving all HTTP requests from the browser
- Bootstrapping the application (loading core files, starting sessions, etc.)
- Registering routes
- Delegating control to the Router to resolve which controller and action to run

As the project grows in complexity, more configuration and setup logic will live here, but it remains a single, central “front door” for every request.

---

## 2. The `core/` Folder – Framework Layer

The `core/` directory contains **framework-level** building blocks: classes that define *how* MVC works in this project, but that are not specific to the blog domain.

It includes things like:

- **Router** – how routes are defined and matched
- **Controller** – a base class that all application controllers extend
- **Storage** – an abstraction for loading and saving data (e.g. JSON files)
- **Flash** – a helper for session-based flash messages (added in later phases)

A key design decision:

> The base `Controller` class lives in `core/`, not in `app/controllers/`.

That’s because it doesn’t represent one concrete controller (like “Posts” or “Home”), but the **general behavior** that all controllers share. The idea is:

- `core/controller.php` → defines the *concept* of a controller in this mini framework
- `app/controllers/*.php` → concrete controllers for specific parts of the application

So you can think of `core/` as a very small custom framework that your app is built on top of.

---

## 3. The `app/` Folder – Application MVC

The `app/` directory contains the actual MVC implementation of the blog: all the parts that are specific to this application.

### 3.1 Controllers (`app/controllers/`)

Controllers receive the resolved request (via the Router) and coordinate between models and views.

Current controllers include:

- `HomeController` – renders the home page
- `PostController` – handles listing, showing, creating, editing, and deleting blog posts
- `CommentsController` handles comment creation and deletion.

Each controller extends the base `Controller` from `core/`, reusing shared logic such as view rendering.

---

### 3.2 Models (`app/models/`)

Models encapsulate the application’s data and the logic that works with that data.

At first, only the `Post` model had real behavior, because posts were the first persistent resource. There is no dedicated `Home` model, since the home page does not manage its own data or storage.

Later phases introduce:

- `Post` – responsible for post data and CRUD operations via file
