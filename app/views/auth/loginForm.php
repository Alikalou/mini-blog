<?php
// Optional: set a page title for the layout
$title = 'Login - Mini Blog';
?>

<h1>Login</h1>

<form action="/login" method="post">
    <div>
        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            required
        >
    </div>

    <div>
        <label for="password">Password</label><br>
        <input
            type="password"
            id="password"
            name="password"
            required
        >
    </div>

    <div style="margin-top: 1rem;">
        <button type="submit">Log in</button>
    </div>
</form>

<p>
    Don’t have an account?
    <a href="/register">Create one</a>.
</p>


<a href="/">
    Return to home page
</a>
