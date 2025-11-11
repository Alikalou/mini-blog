<h1>Welcome to the form page</h1>

<form action="/posts" method="post">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" placeholder="Enter Title" required><br><br>

    <label for="body">Body:</label><br>
    <textarea id="body" name="body" placeholder="Enter Body" rows="5" cols="40" required></textarea><br><br>

    <button type="submit">Click to Submit</button>
</form>

<footer>
    <p><a href="/posts"> Back to Posts</a></p>
    <p><a href="/"> Go to Home</a></p>
    
</footer>

