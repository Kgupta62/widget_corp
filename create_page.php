<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
$not_empty = array('menu_name', 'visible');
$errors = array();

// Validate non-empty fields
foreach ($not_empty as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $errors[] = "{$field} can't be empty";
    }
}

// Validate max lengths
$field_with_lengths = array('menu_name' => 30);
foreach ($field_with_lengths as $fieldname => $max) {
    if (strlen(trim($_POST[$fieldname])) > $max) {
        $errors[] = "{$fieldname} can't be larger than {$max}";
    }
}

// If errors exist, redirect back
if ($errors) {
    header("Location: new_page.php");
    exit;
}

$sel_subj = $_GET['subj'] ?? 0;
?>

<?php
// Escape values using mysqli
$menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
$position  = mysqli_real_escape_string($connection, $_POST['position']);
$visible   = mysqli_real_escape_string($connection, $_POST['visible']);
$content   = mysqli_real_escape_string($connection, $_POST['content']);

// Build query
$query = "INSERT INTO pages (menu_name, position, visible, content, subject_id)
          VALUES ('{$menu_name}', {$position}, {$visible}, '{$content}', {$sel_subj})";

// Execute query
if (mysqli_query($connection, $query)) {
    header("Location: content.php");
    exit;
} else {
    echo "Page creation failed: " . mysqli_error($connection);
}

// Close connection
mysqli_close($connection);
?>
