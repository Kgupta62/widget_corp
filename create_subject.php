<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
$not_empty = array('menu_name', 'position', 'visible');
$errors = array();

// Check for empty fields
foreach ($not_empty as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $errors[] = "{$field} can't be empty";
    }
}

// Check max length
$field_with_lengths = array('menu_name' => 30);
foreach ($field_with_lengths as $fieldname => $max) {
    if (strlen(trim($_POST[$fieldname])) > $max) {
        $errors[] = "{$fieldname} can't be larger than {$max}";
    }
}

// If there are errors, redirect back
if ($errors) {
    header("Location: new_subject.php");
    exit;
}
?>

<?php
// Use mysqli_real_escape_string instead of mysql_real_escape_string
$menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
$position  = mysqli_real_escape_string($connection, $_POST['position']);
$visible   = mysqli_real_escape_string($connection, $_POST['visible']);

// Insert into DB
$query = "INSERT INTO subjects (menu_name, position, visible)
          VALUES ('{$menu_name}', {$position}, {$visible})";

if (mysqli_query($connection, $query)) {
    // Success
    header("Location: content.php");
    exit;
} else {
    echo "Subject creation failed: " . mysqli_error($connection);
}

// Close connection
mysqli_close($connection);
?>
