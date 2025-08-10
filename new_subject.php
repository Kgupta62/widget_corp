<?php require_once("includes/sessions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

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
    $sele_page = get_page_by_id($sel_page);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject - Widget Corp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php include("includes/header.php"); ?>

    <div class="flex flex-1">
        <!-- Navigation -->
        <aside class="w-64 bg-white shadow p-4">
            <?php navigation($sel_subj, $sel_page); ?>
        </aside>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <div class="bg-white rounded-xl shadow-lg p-6 max-w-lg mx-auto">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Add Subject</h2>
                
                <form method="post" action="create_subject.php" class="space-y-5">
                    <!-- Subject Name -->
                    <div>
                        <label for="menu_name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                        <input type="text" name="menu_name" id="menu_name" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <select name="position" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <?php
                              $su = get_all_subjects(false);
                              $number = mysqli_num_rows($su);
                              for ($count = 1; $count <= $number + 1; $count++) {
                                  echo "<option value=\"{$count}\">{$count}</option>";
                              }
                            ?>
                        </select>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <span class="block text-sm font-medium text-gray-700">Visible</span>
                        <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="visible" value="0" class="form-radio text-red-500">
                                <span class="ml-2">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="visible" value="1" class="form-radio text-green-500">
                                <span class="ml-2">Yes</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-between">
                        <a href="content.php" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Subject</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php require("includes/footer.php"); ?>
</body>
</html>
