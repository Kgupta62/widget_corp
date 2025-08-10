<?php require_once("includes/sessions.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
confirm_logged_in();

if (intval($_GET['subj']) == 0) {
    header("Location: content.php");
    exit;
}

$message = "";
$errors = [];

// Handle form submission
if (isset($_POST['submit'])) {
    $not_empty = ['menu_name', 'position', 'visible'];

    foreach ($not_empty as $field) {
        if (!isset($_POST[$field]) || ($_POST[$field] === "" && $_POST[$field] != 0)) {
            $errors[] = "{$field} can't be empty";
        }
    }

    $field_with_lengths = ['menu_name' => 30];
    foreach ($field_with_lengths as $fieldname => $max) {
        if (strlen(trim($_POST[$fieldname])) > $max) {
            $errors[] = "{$fieldname} can't be larger than {$max} characters";
        }
    }

    if (empty($errors)) {
        $id = mysqli_real_escape_string($connection, $_GET['subj']);
        $menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
        $position = (int) $_POST['position'];
        $visible = (int) $_POST['visible'];

        $query = "UPDATE subjects 
                  SET menu_name='{$menu_name}', position={$position}, visible={$visible} 
                  WHERE id={$id} LIMIT 1";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $message = "The subject was successfully edited.";
        } else {
            $message = "The subject update failed.<br>" . mysqli_error($connection);
        }
    } else {
        $message = "There were " . count($errors) . " errors in the form.<br>";
        $message .= "Please review the following fields:<br>-" . implode("<br>-", $errors);
    }
}

// Load selected subject/page
$sel_subj = $_GET['subj'] ?? "";
$sel_page = $_GET['page'] ?? "";

if ($sel_subj) {
    $sel_subject = get_subject_by_id($sel_subj);
}
if ($sel_page) {
    $sel_page_data = get_page_by_id($sel_page);
}
?>

<?php include("includes/header.php"); ?>
<table id="structure">
    <tr>
        <td id="navigation">
            <?php navigation($sel_subj, $sel_page); ?>
        </td>
        <td id="page">
            <p class="message"><?php echo $message; ?></p>
            <?php if (!empty($sel_subject)) { ?>
                <h2>Edit Subject: <?php echo htmlentities($sel_subject['menu_name']); ?></h2>
                <form method="post" action="edit_subject.php?subj=<?php echo urlencode($sel_subj); ?>">
                    <p>Subject Name:
                        <input type="text" name="menu_name" id="menu_name"
                               value="<?php echo htmlentities($sel_subject['menu_name']); ?>">
                    </p>
                    <p>Position:
                        <select name="position">
                            <?php
                            $su = get_all_subjects(false);
                            $number = mysqli_num_rows($su);
                            for ($count = 1; $count <= $number; $count++) {
                                echo "<option value='{$count}'";
                                if ($count == $sel_subject['position']) {
                                    echo " selected";
                                }
                                echo ">{$count}</option>";
                            }
                            ?>
                        </select>
                    </p>
                    <p>Visible:
                        <input type="radio" name="visible" value="0" <?php if ($sel_subject['visible'] == 0) echo "checked"; ?>> No
                        &nbsp;
                        <input type="radio" name="visible" value="1" <?php if ($sel_subject['visible'] == 1) echo "checked"; ?>> Yes
                    </p>
                    <input type="submit" name="submit" value="Edit Subject">
                    &nbsp;&nbsp;
                    <a href="delete_subject.php?subj=<?php echo urlencode($sel_subj); ?>"
                       onclick="return confirm('Are you sure?');">Delete Subject</a>
                </form>
                <br>
                <a href="content.php">Cancel</a>
            <?php } else { ?>
                <p>Subject not found.</p>
            <?php } ?>
        </td>
    </tr>
</table>
<?php require("includes/footer.php"); ?>
