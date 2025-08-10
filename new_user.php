<?php require_once("includes/sessions.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if (isset($_POST['submit'])) {

    $not_empty = array('username', 'password');
    $errors = array();
    foreach ($not_empty as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $errors[] = "{$field} can't be empty";
        }
    }

    $field_with_lengths = array('username' => 30);
    foreach ($field_with_lengths as $fieldname => $max) {
        if (strlen(trim($_POST[$fieldname])) > $max) {
            $errors[] = "{$fieldname} can't be larger than {$max}";
        }
    }

    if (empty($errors)) {
        global $connection;

        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $hashed_password = sha1($_POST['password']);

        $query = "INSERT INTO users (username, hashed_password) 
                  VALUES ('{$username}', '{$hashed_password}')";
        $result = mysqli_query($connection, $query);

        if ($result) {
            $message = "The user was successfully added";
        } else {
            $message = "The user can't be added";
            $message .= "<br>" . mysqli_error($connection);
        }

    } else {
        $message = "There were " . count($errors) . " error(s) in the form.";
        echo "<p>";
        echo "Please review the following fields:<br>";
        foreach ($errors as $error) {
            echo "- " . htmlspecialchars($error) . "<br>";
        }
        echo "</p>";
    }
}
?>

<?php include("includes/header.php"); ?>
<table id="structure">
    <tr>
        <td id="navigation">
            <a href="staff.php">Return to menu</a>
        </td>
        <td align="center" id="page">
            <p class="message"><?php if (isset($message)) { echo $message; $message = ""; } ?></p>
            <h2>Add a new user</h2>
            <div class="page_content">
                <form action="new_user.php" method="post">
                    <p>Username: <input type="text" name="username"></p>
                    <p>Password: <input type="password" name="password"></p>
                    <p><input type="submit" value="Create User" name="submit"></p>
                </form>
            </div>
        </td>
    </tr>
</table>
<?php require("includes/footer.php"); ?>
