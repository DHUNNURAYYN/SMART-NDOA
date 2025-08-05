<?php
include '../connection.php';
include '../session_check.php';

// Handle delete request
if (isset($_GET['delete'])) {
    $news_id = intval($_GET['delete']);

    // Optional: Delete the image from uploads folder (if used)
    $getImg = $conn->query("SELECT image FROM news WHERE news_id = $news_id");
    if ($getImg && $getImg->num_rows > 0) {
        $imgRow = $getImg->fetch_assoc();
        $imgPath = 'uploads/' . $imgRow['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }

    // Delete from DB
    $conn->query("DELETE FROM news WHERE news_id = $news_id");
    header("Location: view_news.php"); // Redirect to refresh
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View News</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../dashboard.css">
    <style>
        .news-container {
            max-width: 95%;
            margin-top: 20px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-height: 80vh;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background-color:#228B22;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img.news-img {
            width: 150px;
            height: auto;
            border-radius: 5px;
        }

        .content-cell {
            white-space: pre-wrap;
            max-width: 400px;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

      .delete-btn {
    background-color: #cc0000;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.delete-btn:hover {
    background-color: #990000;
}

    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>All Posted News</h1>
        </header>

        <div class="news-container">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Date Posted</th>
                        <th>Content</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM news ORDER BY date_posted DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0):
                        $sn = 1;
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="News Image" class="news-img">
                            <?php else: ?>
                                <span>No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo $row['date_posted']; ?></td>
                        <td class="content-cell"><?php echo htmlspecialchars($row['content']); ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['news_id']; ?>" 
                            onclick="return confirm('Are you sure you want to delete this news?');" 
                            title="Delete" 
                            style="color: #e74c3c; font-size: 18px; display:inline-block;">
                                <i class="fas fa-trash-alt"></i>
                            </a>

                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                        echo "<tr><td colspan='6'>No news found.</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
