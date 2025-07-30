<!DOCTYPE html>
<html>
<head>
    <title>Leave a Comment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .comment-form {
            width: 400px;
            margin: 40px auto;
        }

        .comment-form h2 {
            font-size: 18px;
            color: #333;
        }

        .comment-form input[type="text"],
        .comment-form input[type="email"],
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .comment-form textarea {
            height: 100px;
            resize: vertical;
        }

        .comment-form label {
            display: block;
            margin-bottom: 5px;
        }

        .comment-form input[type="checkbox"] {
            margin-right: 5px;
        }

        .comment-form button {
            background-color: #7f9c2e;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
        }

        .comment-form button:hover {
            background-color: #6e8b26;
        }
    </style>
</head>
<body>
    <div class="comment-form">
        <h2>Leave a Comment</h2>
        <form>
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email">

            <label for="url">URL</label>
            <input type="text" id="url" name="url">

            <label for="message">Message</label>
            <textarea id="message" name="message"></textarea>

            <label>
                <input type="checkbox" name="notify"> Notify me of followup comments via e-mail
            </label>

            <br><br>
            <button type="submit">SUBMIT COMMENT</button>
        </form>
    </div>
</body>
</html>
