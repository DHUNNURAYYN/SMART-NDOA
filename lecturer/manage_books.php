<?php
include '../connection.php'; // DB connection
include '../session_check.php';

// Delete Book
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete the file first
    $getFile = mysqli_query($conn, "SELECT file_path FROM books WHERE book_id = $id");
    if ($getFile && mysqli_num_rows($getFile) > 0) {
        $fileRow = mysqli_fetch_assoc($getFile);
        $filePath = $fileRow['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath); // remove file from folder
        }
    }

    // Then delete from DB
    mysqli_query($conn, "DELETE FROM books WHERE book_id = $id");
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
        }
        .btn-edit, .btn-delete, .btn-download {
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 5px;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: black;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        thead {
            background-color: #228B22;
        }
        .add-book {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <!-- Sidebar -->
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
                        $result = mysqli_query($conn, "
                            SELECT books.*, users.full_name 
                            FROM books 
                            JOIN users ON books.uploaded_by = users.user_id 
                            ORDER BY uploaded_on DESC
                        ");
                        $serial = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $serial++;
                            echo "<tr>
                                <td>$serial</td>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td>{$row['category']}</td>
                                <td>{$row['full_name']}</td>
                                <td>{$row['uploaded_on']}</td>
                                <td>
                                    <a href='{$row['file_path']}' class='btn-download' download><i class='fas fa-download'></i> Download</a>
                
                                    <a href='?delete={$row['book_id']}' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this book?\")'><i class='fas fa-trash'></i> Delete</a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
                                <a href="add_books.php" class="add-book"><i class="fas fa-plus"></i> Add New Book</a>

            </div>
        </div>
    </div>
</body>
</html>
