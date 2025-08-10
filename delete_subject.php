<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if (intval($_GET['subj']) == 0) {
    header("Location: content.php");
    exit;
}

$id = mysqli_real_escape_string($connection, $_GET['subj']);

if ($subject = get_subject_by_id($id)) {
    $query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) == 1) {
        header("Location: content.php");
        exit;
    } else {
        echo "<div class='text-red-600 font-bold'>Subject deletion failed</div><br>";
        echo "<div class='text-sm'>" . mysqli_error($connection) . "</div><br>";
        echo "<a class='text-blue-500 underline' href=\"content.php\">Return to main page</a>";
    }
} else {
    echo "<div class='text-yellow-600 font-semibold'>No such subject exists</div>";
    header("Location: content.php");
    exit;
}
?>

<?php require("includes/footer.php"); ?>
