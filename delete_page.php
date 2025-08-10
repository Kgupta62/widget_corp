<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if (intval($_GET['page'] ?? 0) == 0) {
    header("Location: content.php");
    exit;
}

$id = mysqli_real_escape_string($connection, $_GET['page']);

// Check if page exists
if ($pagess = get_page_by_id($id)) {

    $query  = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection, $query);

    if (mysqli_affected_rows($connection) == 1) {
        header("Location: content.php");
        exit;
    } else {
        echo "Page deletion failed<br>";
        echo mysqli_error($connection) . "<br>";
        echo '<a href="content.php">Return to main page</a>';
    }

} else {
    echo "No such page exists";
    header("Location: content.php");
    exit;
}
?>

<?php require("includes/footer.php"); ?>
