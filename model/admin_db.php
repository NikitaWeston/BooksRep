<?php
function add_admin($email, $password) {
    global $db;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query = 'INSERT INTO Users (Emaillogin, Passwordhash)
              VALUES (:email, :password)';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $hash);
    $statement->execute();
    $statement->closeCursor();
}

function is_valid_admin_login($email, $password) {
    global $db;
    
    //add_admin($email, $password);

    $query = 'SELECT Passwordhash FROM Users
              WHERE Emaillogin = :email';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $row = $statement->fetch();
    $statement->closeCursor();

    if($row === False)
    {
        return False;
    }
    
    $hash = $row['Passwordhash'];
    return password_verify($password, $hash);
}
?>