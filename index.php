<?php

/*

We want ch21_admin to work, but we ran into issues:

1) Problem: Access denied for user 'mgs_user'@'localhost' (using password: YES)
      - Database is not set up
   Solution: Run the SQL code that sets up the DB

2) Problem: Redirecting to HTTPs (via the file util/secure_conn.php)
   Solution: Comment out the redirection code

2.5) Problem: Notice: Trying to access array offset on value of type bool in C:\xampp\htdocs\book_apps\ch21_admin\model\admin_db.php on line 23

3) Problem: Can't log in, don't know password
   Solution: 

*/



// Start session management and include necessary functions
session_start();
require_once('model/database.php');
require_once('model/admin_db.php');

// Get the action to perform
$action = filter_input(INPUT_POST, 'action');

if ($action == NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action == NULL) {
        $action = 'show_admin_menu';
    }
}

// If the user isn't logged in, force the user to login
if (!isset($_SESSION['is_valid_admin'])) {
    $action = 'login';
}

// Perform the specified action
switch($action) {
    case 'login':
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        if (is_valid_admin_login($email, $password)) {
            $_SESSION['is_valid_admin'] = true;
            $_SESSION['currently_logged_in_user_username'] = $email;
            $stmt = $db->prepare("SELECT * FROM users WHERE Emaillogin = :username"); 
            $stmt->bindParam(":username", $email);
            $stmt->execute();
            $row = $stmt->fetch();
            $user_id = $row["UserID"];
            $_SESSION['currently_logged_in_user_id'] = $user_id;
            include('view/admin_menu.php');
        } else {
            $login_message = 'You must login to view this page.';
            include('view/login.php');
        }
        break;
    case 'left_off':
        include("view/left_off.php");
        break;
    case 'add_books':
        include('view/add_books.php');
        break; 
    case 'show_admin_menu':
        include('view/admin_menu.php');
        break;
    case 'show_product_manager':
        include('view/product_manager.php');
        break;
    case 'show_order_manager':
        include('view/order_manager.php');
        break;
    case 'logout':
        $_SESSION = array();   // Clear all session data from memory
        session_destroy();     // Clean up the session ID
        $login_message = 'You have been logged out.';
        include('view/login.php');
        break;
}
?>