USE bookrepository;
-- Joins users and BooksRead
CREATE TABLE sessions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT,
   booksread_id INT,
   FOREIGN KEY (user_id) REFERENCES Users(UserID),
   FOREIGN KEY (booksread_id) REFERENCES BooksRead(BooksReadID)
);