<?php require_once("includes/sessions.php"); ?>
<?php
  if(loggedin()) {
    header("Location: staff.php");
    exit;
  }
?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if(isset($_POST['submit'])) {
    $not_empty = array('username','password');
    $errors = array();
    foreach($not_empty as $field) {
        if(!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = "{$field} can't be empty";
        }
    }

    $field_with_lengths = array('username' => 30);
    foreach($field_with_lengths as $fieldname => $max) {
        if(strlen(trim($_POST[$fieldname])) > $max) {
            $errors[] = "{$fieldname} can't be larger than {$max}";
        }
    }

    if(!$errors) {
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $hashed_password = sha1($_POST['password']);

        $query = "SELECT * FROM users WHERE username='{$username}' AND hashed_password='{$hashed_password}'";
        $resul = mysqli_query($connection, $query);
        $userc = mysqli_fetch_array($resul, MYSQLI_ASSOC);

        if($userc) {
            $_SESSION['user_id'] = $userc['id'];
            $_SESSION['username'] = $userc['username'];
            header("Location: staff.php");
            exit;
        } else {
            $message = "The user credentials are wrong";
            $message .= "<br>" . mysqli_error($connection);
        }
    } else {
        $message = "There were ".count($errors)." errors in the form.";
        $error_list = "<ul class='list-disc pl-5 text-red-500'>";
        foreach($errors as $error) {
            $error_list .= "<li>- {$error}</li>";
        }
        $error_list .= "</ul>";
    }
} else {
    if(isset($_GET['logout']) && $_GET['logout'] == 1) {
        $message="You have been successfully logged out";
    }
    $username = "";
    $password = "";
}
?>

<?php include("includes/header.php"); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
    <a href="index.php" class="text-blue-600 hover:underline block mb-4">← Return to public site</a>

    <?php if(isset($message) && $message != ""): ?>
      <div class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <?php if(isset($error_list)) echo $error_list; ?>

    <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">Staff Login</h2>

    <form action="login.php" method="post" class="space-y-4">
      <div>
        <label class="block text-gray-600 mb-1">Username</label>
        <input type="text" name="username" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
      </div>
      <div>
        <label class="block text-gray-600 mb-1">Password</label>
        <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
      </div>
      <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Login</button>
    </form>
  </div>
</div>
<?php require("includes/footer.php"); ?>
