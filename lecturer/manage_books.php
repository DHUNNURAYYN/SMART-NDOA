<?php
include '../connection.php'; // DB connection
include '../session_check.php';

// Delete Book
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete']; // Cast to int for safety

    // Prepare statement to select file_path
    $stmt = $conn->prepare("SELECT file_path FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $fileRow = $result->fetch_assoc();
        $filePath = $fileRow['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath); // remove file from folder
        }
    }
    $stmt->close();

    // Prepare delete statement
    $del_stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $del_stmt->bind_param("i", $id);
    $del_stmt->execute();
    $del_stmt->close();

    header("Location: manage_books.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        tbody {
            background-color: white;
        }
        th {
            color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        thead {
            background-color: #228B22;
        }
        .icon-action {
            font-size: 18px;
            margin-right: 10px;
            text-decoration: none;
        }
        .icon-download {
            color: #28a745;
        }
        .icon-delete {
            color: #e74c3c;
        }
        .add-book {
            display: inline-block;
            color: #007bff;
            font-size: 50px;
            margin-top: 10px;
            text-decoration: none;
        }
        .add-book:hover {
            opacity: 0.8;
        }
        .add-book-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 50%;
            width: 100px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <?php include '../sidebar.php'; ?>
    </div>

    <div class="main-content">
        <header>
            <h1>Manage Books</h1>
        </header>
        <div class="books">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Uploaded By</th>
                        <th>Uploaded On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("
                        SELECT books.*, users.full_name 
                        FROM books 
                        JOIN users ON books.uploaded_by = users.user_id 
                        ORDER BY uploaded_on DESC
                    ");
                    $serial = 0;
                    while ($row = $result->fetch_assoc()) {
                        $serial++;
                        echo "<tr>
                            <td>" . htmlspecialchars($serial) . "</td>
                            <td>" . htmlspecialchars($row['title']) . "</td>
                            <td>" . htmlspecialchars($row['author']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>" . htmlspecialchars($row['full_name']) . "</td>
                            <td>" . htmlspecialchars($row['uploaded_on']) . "</td>
                            <td>
                                <a href='" . htmlspecialchars($row['file_path']) . "' title='Download' class='icon-action icon-download' download>
                                    <i class='fas fa-download'></i>
                                </a>
                                <a href='?delete=" . htmlspecialchars($row['book_id']) . "' title='Delete' class='icon-action icon-delete' onclick='return confirm(\"Are you sure you want to delete this book?\")'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Add Book Icon -->
            <div class="add-book-container">
                <a href="add_books.php" class="add-book" title="Add New Book">
                    <i class="fas fa-plus-circle"></i>
                </a>
            </div>  
        </div>
    </div>
</div>
</body>
</html>
