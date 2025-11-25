# Authentication & Authorization

## How authentication works

The blog uses a very simple session-based authentication layer implemented in `core/auth.php` and the `User` model.

1. A visitor submits the login form with email and password.
2. The controller calls the `User` model to look up the user by email and verify the password.
3. If the credentials are valid, the `Auth` helper stores the user’s ID in the PHP session:
   - `$_SESSION['user_id'] = $user['id'];`
4. On each request, `Auth::check()` returns `true` if `$_SESSION['user_id']` is set.
5. `Auth::userId()` returns the currently authenticated user’s ID (or `null` if not logged in).
6. The base `Controller` uses `Auth::check()` and `Auth::userId()` to load the current user and pass it to all views as 
`$data['user']`.

Logging out simply clears the user ID from the session (e.g. `unset($_SESSION['user_id']);`).

---

## Public vs protected routes

Some routes can be accessed by anyone (guests and logged-in users), while others are protected and require authentication. Protection is implemented via the router’s `auth` guard and manual `Auth::check()` / `Auth::userId()` checks in controllers.

### Public routes

These routes are accessible without being logged in:

- `GET /`  
  Home page / redirect to posts.
- `GET /posts`  
  List all posts.
- `GET /posts/show/{id}`  
  Show a single post and its comments.
- `GET /login`  
  Show the login form.
- `POST /login`  
  Process login.
- `GET /register`  
  Show the registration form.
- `POST /register`  
  Process registration.
- `GET /logout`  
  Log out the current user.

(Exact list may vary slightly depending on your router file, but the idea is: **viewing** content and **auth forms** are public.)

### Protected routes (require authentication)

These routes are protected with the `auth` guard and/or explicit `Auth::check()` calls. They require a logged-in user:

- `GET /posts/create`  
  Show the “create post” form.  
  Guard: `auth` + extra safety in `PostController::form()`.
- `POST /posts`  
  Create a new post.  
  Uses `Auth::userId()` as `author_id`.
- `GET /posts/edit/{id}`  
  Show the “edit post” form (only for the owner). (Edition is not implemented yet)
- `POST /posts/{id}`  
  Update an existing post (only for the owner).
- `POST /posts/delete/{id}`  
  Delete a post (only for the owner).
- `POST /posts/{id}/comments`  
  Add a comment to a post (requires being logged in).
- `POST /posts/{postId}/comments/delete/{commentId}`  
  Delete a comment (only for the comment’s owner).

---

## How ownership is enforced (posts & comments)

### Posts

Ownership of posts is enforced in `PostController`:

- Every post stores the user who created it in `author_id`.
- When updating a post (`store($id)` with a non-null `$id`), the controller:
  - Loads the existing post with `$this->post->findPost($id)` into `$tempPost['author_id']`.
  - Compares `Auth::userId()` with `$tempPost['author_id']`.
  - Only proceeds with `update()` if they match; otherwise it sets a flash message and redirects.
- When deleting a post (`destroy($id)`), the controller:
  - Loads the post similar to the first step above.
  - Checks `Auth::userId()` against `$tempPost['author_id']`.
  - Only calls `$this->post->delete($id)` if the current user is the owner.
- Views (e.g. `show.php`) also only show “Delete” button when:
  - `$userId !== null && $userId === $post['author_id']`.

### Comments

Ownership of comments is enforced similarly in `CommentController`:

- Each comment stores:
  - `post_id` → which post it belongs to,
  - `author_id` → which user wrote it.
- When deleting a comment, the controller:
  - Loads the existing comment with `$this->comment->findComment( $commentId )` into `$tempComment['author_id']`.
  - Compares `Auth::userId()` with `$comment['author_id']`.
  - Only allows deletion if they match; otherwise it shows a flash message and redirects.
- In the post show view, the “Delete Comment” button is only shown if:
  - `$userId !== null && $userId === $comment['author_id']`.

In other words, **only the user who created a post or comment can edit or delete it**.

---

## Session key for the authenticated user

The authenticated user is stored in the session under a single key:

- **Session key:** `user_id`  
- **Location:** `$_SESSION['user_id']`

The `Auth` helper wraps this:

- `Auth::check()` → `isset($_SESSION['user_id'])`
- `Auth::userId()` → returns `$_SESSION['user_id']` (or `null`)

All authentication and ownership checks are built on top of this session value.
