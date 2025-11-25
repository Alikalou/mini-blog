<?php
// Optional: set a page title for the layout
$title = 'Register - Mini Blog';
?>

<h1>Register</h1>

<form action="/register" method="post">
    <div>
        <label for="name">Name</label><br>
        <input
            type="text"
            id="name"
            name="name"
            required
        >
    </div>

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
            minlength="10"
            required
        >
    </div>

    <div>
        <label for="passwordConf">Confirm Password</label><br>
        <input
            type="password"
            id="passwordConf"
            name="passwordConf"
            minlength="10"
            required
        >
    </div>

    <div style="margin-top: 1rem;">
        <button type="submit">Create account</button>
    </div>
</form>

<p>
    Already have an account?
    <a href="/login">Log in here</a>.
</p>

<a href="/">
    Return to home page
</a>
