# Data Models (Schemas)

This document describes the JSON-based data schemas used in the Mini Blog project.
Each model section explains the stored fields, their meaning, and where they are used.

---

## 1. User Model

**Storage:** `storage/users.json`  
**Class:** `app/models/User.php`

| Field        | Type   | Description |
|--------------|--------|-------------|
| id           | int    | Auto-increment user ID |
| name         | string | Display name |
| email        | string | Unique email address |
| password     | string | Password hash |

---

## 2. Post Model

**Storage:** `storage/posts.json`  
**Class:** `app/models/Post.php`

| Field       | Type   | Description |
|-------------|--------|-------------|
| id          | int    | Auto-increment post ID |
| title       | string | Post title |
| body        | string | Main content |
| author_id   | int    | User who wrote the post |
| author      | string | (Legacy) Author name for old posts |

---

## 3. Comment Model

**Storage:** `storage/comments.json`  
**Class:** `app/models/Comment.php`

| Field        | Type   | Description |
|--------------|--------|-------------|
| id           | int    | Auto-increment comment ID |
| post_id      | int    | The post this comment belongs to |
| author_id    | int    | User who wrote the comment |
| author       | string | Author name |
| body         | string | Comment text |
| created_at   | string | Timestamp |


## Notes on Legacy Data

Some old posts may not include `author` or `author_id`.  
Views include safe guards (`isset()` / `??`) to handle missing values.
