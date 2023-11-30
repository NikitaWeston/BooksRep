<?php
    require_once('util/secure_conn.php');  // require a secure connection
    require_once('util/valid_admin.php');  // require a valid admin user
// Check if form was submitted
    if (isset($_POST['submit'])) {
        $UserID = $_SESSION['currently_logged_in_user_id'];
        $BookID = $_POST['BookID'];
        $DateRead = $_POST['DateRead'];
        $ReviewDate = $_POST['ReviewDate'];
        $ReviewText = $_POST['ReviewText'];
        $Rating = $_POST['Rating'];

        // Prepare INSERT statement to avoid SQL injection
        $stmt = $db->prepare('INSERT INTO BooksRead (UserID, BookID, DateRead, ReviewDate, ReviewText, Rating ) VALUES 
        (:UserID, :BookID, :DateRead, :ReviewDate, :ReviewText, :Rating)');
        
        // Bind parameters
        $stmt->bindParam(':UserID', $UserID);
        $stmt->bindParam(':BookID', $BookID);
        $stmt->bindParam(':DateRead', $DateRead);
        $stmt->bindParam(':ReviewDate', $ReviewDate);
        $stmt->bindParam(':ReviewText', $ReviewText);
        $stmt->bindParam(':Rating', $Rating);
        // Execute statement
        $stmt->execute();
        echo "You added a book successfully";

    }
if(isset($_SESSION['currently_logged_in_user_id'])){
    $UserID = $_SESSION['currently_logged_in_user_id'];
    $stmt = $db->prepare('SELECT Books.Title, Books.BookID,DateRead,ReviewDate,ReviewText,Rating FROM BooksRead JOIN Books ON Books.BookID = BooksRead.BookID WHERE BooksRead.UserID = :UserID' );
    $stmt->bindParam(':UserID', $UserID);
    $stmt->execute();
}
else
{
    throw new Exception('Should not be able to get here if not logged in');

}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>My books</title>
        <link rel="stylesheet" type="text/css" href="main.css"/>
    <style>
           
        .container {
            margin: 10 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-radius: 8px;
        }
       
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            
        }
        table, th, td {
            border:  solid black ;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #D8C3A5;
            color: black;
        }
        table tr:nth-child(even) {
            background-color: #D8C3A5;
        }
        table tr:hover {
            background-color: #ddd;
        }
        .navbar {
        background-color:#D8C3A5; /* Background color of the navigation bar */
        text-align: left; /* Align the text content to the left within the navbar */
        font-size: 20px;
        }
        .navbar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        }
        .navbar li {
        display: inline-block; /* Display the list items horizontally */
        margin-right: 20px; /* Add some spacing between the list items */
        }
        .navbar a {
        text-decoration: none; /* Remove underlines from links */
        color: #8b4513; /* Text color of the links */
        }
    </style>
    </head>
    <body>
    <style type="text/css">
    
</style>
        <header>
            <h1>Book Repository</h1>
        </header>
        <?php
            include("util/nav_menu.php")
        ?>
        <!--  BooksRead table here??  -->
        <div class="container">
            <h2>Read Books so far...</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>DateRead</th>
                    <th>ReviewDate</th>
                    <th>ReviewText</th>
                    <th>Rating</th>
                </tr>
                <?php while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td>
                        <td><?php echo htmlspecialchars($row['DateRead']); ?></td>
                        <td><?php echo htmlspecialchars($row['ReviewDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['ReviewText']); ?></td>
                        <td><?php echo htmlspecialchars($row['Rating']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="container">
                <h1>Add New Books</h2>
                <form method="post">
                    <label for="registry">Title:</label>
                    <select name="BookID" id="BookID" >
                    <option value=""></option>
                    <?php
                        $result = $db->query("SELECT * FROM Books");

                        while ($row = $result->fetch()) {
                            echo "<option value='" . $row["BookID"] . "'>" . htmlspecialchars($row['Title']) . "</option>";
                        }
                    ?>
                    </select>
                    <label for="name">DateRead:</label>
                    <input type="date" id="DateRead" name="DateRead" required>
                    
                    <label for="name">ReviewDate:</label>
                    <input type="date" id="ReviewDate" name="ReviewDate" required>
                   
                    <label for="name">ReviewText:</label>
                    <input type="text" id="ReviewText" name="ReviewText" required>

                    <label for="Rating">Rating:</label>
                    <select id="Rating" name="Rating" required>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
            
                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
        </div>
    </body>
</html>


