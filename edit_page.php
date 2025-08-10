<?php require_once("includes/sessions.php"); ?>
<?php
confirm_logged_in();
?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if (isset($_POST['submit'])) {

    $not_empty = array('menu_name', 'position', 'visible');
    $errors = array();

    foreach ($not_empty as $field) {
        if (!isset($_POST[$field]) || (empty($_POST[$field]) && $_POST[$field] != 0)) {
            $errors[] = "{$field} can't be empty";
        }
    }

    $field_with_lengths = array('menu_name' => 30);
    foreach ($field_with_lengths as $fieldname => $max) {
        if (strlen(trim($_POST[$fieldname])) > $max) {
            $errors[] = "{$fieldname} can't be larger than {$max}";
        }
    }

    if (!$errors) {
        global $connection;

        $id = mysqli_real_escape_string($connection, $_GET['page']);
        $menu_name = mysqli_real_escape_string($connection, $_POST['menu_name']);
        $position = mysqli_real_escape_string($connection, $_POST['position']);
        $visible = mysqli_real_escape_string($connection, $_POST['visible']);
        $content = mysqli_real_escape_string($connection, $_POST['content']);

        $query = "UPDATE pages 
                  SET menu_name='{$menu_name}', content='{$content}', position={$position}, visible={$visible} 
                  WHERE id={$id}";

        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) == 1) {
            $message = "The page was successfully edited";
        } else {
            $message = "The page update failed";
            $message .= "<br>" . mysqli_error($connection);
        }
    } else {
        $message = "There were " . count($errors) . " error(s) in the form.";
        echo "<p>Please review the following fields:<br>";
        foreach ($errors as $error) {
            echo "-" . $error . "<br>";
        }
        echo "</p>";
    }
}
?>

<?php
if (isset($_GET['subj'])) {
    $sel_subj = $_GET['subj'];
    $sel_page = "";
} elseif (isset($_GET['page'])) {
    $sel_page = $_GET['page'];
    $sel_subj = "";
} else {
    $sel_subj = "";
    $sel_page = "";
}

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
            <p class="message">
                <?php if (isset($message)) { echo $message; $message = ""; } ?>
            </p>

            <h2>Edit Page: <?php echo $sel_page_data['menu_name']; ?></h2>
            <form method="post" action="edit_page.php?page=<?php echo urlencode($sel_page); ?>">

                <p>Page Name:
                    <input type="text" name="menu_name" id="menu_name" value="<?php echo $sel_page_data['menu_name']; ?>">
                </p>

                <p>Position:
                    <select name="position">
                        <?php
                        $page_set = get_pages_for_subject($sel_page_data['subject_id'], false);
                        $number = mysqli_num_rows($page_set);
                        for ($count = 1; $count <= $number + 1; $count++) {
                            echo "<option value='{$count}'";
                            if ($count == $sel_page_data['position']) {
                                echo " selected";
                            }
                            echo ">{$count}</option>";
                        }
                        ?>
                    </select>
                </p>

                <p>Visible:
                    <input type='radio' name='visible' value='0' <?php if ($sel_page_data['visible'] == '0') echo "checked"; ?>> No &nbsp;
                    <input type='radio' name='visible' value='1' <?php if ($sel_page_data['visible'] == '1') echo "checked"; ?>> Yes
                </p>

                <p>Content:<br><br>
                    <textarea name="content" rows="5" cols="25"><?php echo $sel_page_data['content']; ?></textarea>
                </p>

                <input type="submit" name="submit" value="Edit Page">
                &nbsp;&nbsp;

                <br>
                <a href="delete_page.php?page=<?php echo urlencode($sel_page); ?>" onclick="return confirm('Are you sure?');">Delete Page</a>
            </form>
            <a href="content.php">Cancel</a>
        </td>
    </tr>
</table>
<?php require("includes/footer.php"); ?>
