<?php session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Forums</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style type="text/css" media="screen">
        a:link {
            color: white;
            text-decoration: none;
        }

        a:visited {
            color: white;
            text-decoration: none;
        }

        a:hover {
            color: white;
            text-decoration: none;
        }

        a:active {
            color: white;
            text-decoration: none;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .column {
            float: left;
            width: 20%;
            padding: 10px;
        }

        .row {
            margin: 0 -5px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            padding: 10px;
            text-align: center;
            background-color: #f1f1f1;
            text-decoration: none;
            border-radius: 15px;
            color: white;
            transform: scale(0.95);
            border: none;
        }

        h3 {
            font-size: 1.17em;
        }

        .bx {
            font-size: 20pt;
            margin-top: 10px;
        }

        footer {
            margin: 10px;
            width: 100%;
            height: 20px;
            text-align: center;
            color: #57923d;
            font-size: small;
        }

        .topnav {
            overflow: hidden;
        }

        .topnav input[type=text] {
            float: right;
            padding: 8px;
            border: none;
            outline: none;
            margin-top: 8px;
            margin-right: 16px;
            font-size: 15px;
            background-color: #F1F1F1;
            border-radius: 8px;
            color: #898989;
            width: 20%;
            -webkit-transition: width 0.15s ease-in-out;
            transition: width 0.15s ease-in-out;
        }

        .tabs {
            display: flex;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .tab {
            flex: 1;
            padding: 10px;
            background: #ddd;
            text-align: center;
            border: 1px solid #ccc;
        }

        .tab.active {
            background: #fff;
            border-bottom: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: vertical;
        }

        .posts {
            margin-top: 20px;
        }

        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .post strong {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php include 'head.php'; ?>

    <div class="container">
        <div class="tabs">
            <div class="tab active" onclick="showTab('createForumTab')">Create Forum Post</div>
            <div class="tab" onclick="showTab('readPostsTab')">Read Posts</div>
        </div>

        <div id="createForumTab" class="tab-content active">
            <div class="form-group">
                <h2>Create Forum</h2>
                <label for="forumTitle">Forum Title</label>
                <input type="text" id="forumTitle" placeholder="Forum Title">
                <button class="btn btn-primary" onclick="createForum()">Create Forum</button>
            </div>

            <div class="form-group">
                <h2>Submit Post</h2>
                <label for="forumTitle">Forum Title</label>
                <select id="forumDropdown" placeholder="Select Forum Title">
                    <!-- Options will be populated by JavaScript -->
                </select>
                <input type="hidden" id="userId" placeholder="User ID" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                <label for="postContent">Post Content</label>
                <textarea id="postContent" placeholder="Post Content"></textarea>
                <button class="btn btn-primary" onclick="submitPost()">Submit Post</button>
            </div>
        </div>

        <div id="readPostsTab" class="tab-content">
            <div class="form-group">
                <h2>Fetch Posts</h2>
                <select id="forumDropdownV2" placeholder="Select Forum Title">
                    <!-- Options will be populated by JavaScript -->
                </select>
                <button class="btn btn-primary" onclick="fetchPosts()">Fetch Posts</button>
                <div id="postsContainer" class="posts"></div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }

        function createForum() {
            const title = document.getElementById('forumTitle').value;

            $.ajax({
                url: '../api/forums.php',
                type: 'GET',
                data: {
                    operation: 'create_forum',
                    project_id: 1,
                    title
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire('Success', `Forum created`, 'success');
                    document.getElementById('forumTitle').value = '';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch('../api/forums.php?operation=fetch_forums')
                .then(response => response.json())
                .then(data => {
                    const forumTitleSelect = document.getElementById('forumDropdown');
                    data.forEach(forum => {
                        const option = document.createElement('option');
                        option.value = forum.forum_id; // Use forum_id from the response
                        option.textContent = forum.title; // Use title from the response
                        forumTitleSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching forums:', error));
        });

        document.addEventListener('DOMContentLoaded', function() {
            fetch('../api/forums.php?operation=fetch_forums')
                .then(response => response.json())
                .then(data => {
                    const forumTitleSelect = document.getElementById('forumDropdownV2');
                    data.forEach(forum => {
                        const option = document.createElement('option');
                        option.value = forum.forum_id; // Use forum_id from the response
                        option.textContent = forum.title; // Use title from the response
                        forumTitleSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching forums:', error));
        });


        function submitPost() {
            const forumId = document.getElementById('forumDropdown').value;
            const userId = document.getElementById('userId').value;
            const content = document.getElementById('postContent').value;

            $.ajax({
                url: '../api/forums.php',
                type: 'GET',
                data: {
                    operation: 'submit_post',
                    forum_id: forumId,
                    user_id: userId,
                    content
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire('Success', `Post submitted`, 'success');
                    document.getElementById('postContent').value = '';
                }
            });
        }

        function fetchPosts() {
            const forumId = document.getElementById('forumDropdownV2').value;

            $.ajax({
                url: '../api/forums.php',
                type: 'GET',
                data: {
                    operation: 'fetch_posts',
                    forum_id: forumId
                },
                dataType: 'json',
                success: function(response) {
                    const postsContainer = document.getElementById('postsContainer');
                    postsContainer.innerHTML = '';

                    response.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.classList.add('post');
                        postElement.innerHTML = `
                            <strong>${post.username}</strong>
                            <p>${post.content}</p>
                        `;
                        postsContainer.appendChild(postElement);
                    });
                }
            });
        }
    </script>
</body>

</html>