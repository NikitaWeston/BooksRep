<?php
    require_once('util/secure_conn.php');  // require a secure connection
    require_once('util/valid_admin.php');  // require a valid admin user
// Check if form was submitted
    if (isset($_POST['submit'])) {
        $Title = $_POST['Title'];
        $AuthorID = $_POST['AuthorID'];
        $GenreName = $_POST['GenreName'];
        $ISBN = $_POST['ISBN'];
        $Cost = $_POST['Cost'];
        $Length = $_POST['Length'];
        
        // Preparing a select statement to find genreID
        $stmt = $db->prepare('SELECT BookGenreID FROM bookgenre WHERE GenreName = :GenreName');

        $stmt->bindParam(':GenreName', $GenreName);
       
       // $stmt->execute([$GenreName]);
        // fetch column from result ans tores GenreID
        $stmt->execute();
        $BookGenreID = $stmt->fetch()[0];;
        $stmt->closeCursor();

         // Prepare INSERT statement to avoid SQL injection
         $stmt = $db->prepare('INSERT INTO Books (Title,AuthorID,BookGenreID,ISBN,Cost,Length ) VALUES 
         (:Title, :AuthorID, :BookGenreID, :ISBN, :Cost, :Length)');
         
         // Bind parameters
         $stmt->bindParam(':Title', $Title);
         $stmt->bindParam(':AuthorID', $AuthorID);
         $stmt->bindParam(':BookGenreID', $BookGenreID);
         $stmt->bindParam(':ISBN', $ISBN);
         $stmt->bindParam(':Cost', $Cost);
         $stmt->bindParam(':Length', $Length);

         // Execute statement
         if($stmt->execute()){
            echo "You added a book successfully";
         }
         
    }

$stmt = $db->query('SELECT Title,AuthorID,GenreName, ISBN,Cost,Length FROM Books Join BookGenre on BookGenre.BookGenreID = Books.BookGenreID ' );
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
        <header>
            <h1>Book Repository</h1>
        </header>
        <?php
            include("util/nav_menu.php")
        ?>
        <!--  BooksRead table here??  -->
        <div class="container">
            <h2>New Books</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>AuthorID</th>
                    <th>Book Genre</th>
                    <th>ISBN</th>
                    <th>Cost</th>
                    <th>Length</th>
                </tr>
                <?php while ($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td>
                        <td><?php echo htmlspecialchars($row['AuthorID']); ?></td>
                        <td><?php echo htmlspecialchars($row['GenreName']); ?></td>
                        <td><?php echo htmlspecialchars($row['ISBN']); ?></td>
                        <td><?php echo htmlspecialchars($row['Cost']); ?></td>
                        <td><?php echo htmlspecialchars($row['Length']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="container">
                <h1>Add New Books</h2>
                <form method="post">
                    <label for="name">Title:</label>
                    <input type="text" id="Title" name="Title" required>

                    <label for="name">AuthorID:</label>
                    <input type="text" id="AuthorID" name="AuthorID" required>

                    <label for="registry">ISBN:</label>
                    <input type="text" id="ISBN" name="ISBN" required>
                    
                    <label for="name">Cost:</label>
                    <input type="text" id="Cost" name="Cost" required>
                    
                    <label for="name">Length:</label>
                    <input type="text" id="Length" name="Length" required>

                    <label for="GenreName">Genre:</label>
                    <select id="GenreName" name="GenreName" required>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-fiction">Non-fiction</option>
                        <option value="Horror">Horror</option>
                        <option value="Science-fiction">Science-fiction</option>
                        <option value="Romance">Romance</option>
                    </select>
            
                    <input type="submit" name="submit" value="Submit">
                </form>
            </div>
        </div>
    </body>
</html>