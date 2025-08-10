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
            $message = "✅ The user was successfully added";
        } else {
            $message = "❌ The user can't be added";
            $message .= "<br>" . mysqli_error($connection);
        }

    } else {
        $message = "⚠️ There were " . count($errors) . " error(s) in the form.";
        $message .= "<ul class='list-disc pl-5 text-red-600'>";
        foreach ($errors as $error) {
            $message .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $message .= "</ul>";
    }
}
?>

<?php include("includes/header.php"); ?>

<div class="max-w-2xl mx-auto py-10 px-6">
    <a href="staff.php" class="text-rose-600 hover:underline">&larr; Return to menu</a>

    <?php if (!empty($message)): ?>
        <div class="mt-4 p-4 rounded-lg 
                    <?php echo (strpos($message, '✅') !== false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h2 class="text-3xl font-bold text-slate-800 mt-6 mb-6">Add a New User</h2>

    <form action="new_user.php" method="post" class="bg-white p-6 rounded-lg shadow-lg space-y-5">
        <div>
            <label for="username" class="block font-medium text-slate-700 mb-1">Username</label>
            <input type="text" name="username" id="username" 
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>

        <div>
            <label for="password" class="block font-medium text-slate-700 mb-1">Password</label>
            <input type="password" name="password" id="password" 
                   class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 focus:outline-none">
        </div>

        <div>
            <button type="submit" name="submit" 
                    class="w-full bg-rose-600 text-white py-2 px-4 rounded-lg hover:bg-rose-700 transition">
                Create User
            </button>
        </div>
    </form>
</div>

<?php require("includes/footer.php"); ?>
