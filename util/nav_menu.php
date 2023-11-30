
<html>
<head>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <span>
    <?php echo htmlspecialchars($_SESSION['currently_logged_in_user_id']); ?> 
    </span>
    <nav class="navbar">
        <ul>
            <li><a href="index.php?action=show_admin_menu">Admin menu</a></li>
            <li><a href="index.php?action=add_books">Add Books</a></li>
            <li><a href="index.php?action=left_off">Where did I leave off?</a></li>
            <li><a href="index.php?action=show_order_manager">Order Manager</a></li>
            <li><a href="index.php?action=logout">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
