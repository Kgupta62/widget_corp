<?php require_once("includes/sessions.php"); ?>
<?php confirm_logged_in(); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
$sel_subj = $_GET['subj'] ?? '';
$sel_page = $_GET['page'] ?? '';

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
    <title>Add New Page - Widget Corp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php include("includes/header.php"); ?>

    <div class="flex flex-1 max-w-7xl mx-auto w-full py-6">
        <!-- Navigation -->
        <aside class="w-72 bg-white shadow-lg rounded-xl p-6 mr-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Navigation</h2>
            <nav class="space-y-3 text-base">
                <?php navigation($sel_subj, $sel_page); ?>
            </nav>
            <a href="new_subject.php" class="block mt-6 text-indigo-600 hover:underline font-medium">
                + Add a new subject
            </a>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Add Page</h2>
            
            <form method="post" action="create_page.php?subj=<?php echo urlencode($sel_subj); ?>" class="space-y-6">
                
                <!-- Page Name -->
                <div>
                    <label for="menu_name" class="block text-sm font-medium text-gray-700">Page Name</label>
                    <input type="text" name="menu_name" id="menu_name" 
                           class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base p-2">
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <select name="position" id="position" 
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base p-2">
                        <?php
                        $page_set = get_pages_for_subject($sel_subj);
                        $number = mysqli_num_rows($page_set);
                        for ($count = 1; $count <= $number + 1; $count++) {
                            echo "<option value='{$count}'>{$count}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Visible -->
                <div>
                    <span class="block text-sm font-medium text-gray-700">Visible</span>
                    <div class="mt-2 flex space-x-6">
                        <label class="flex items-center text-base">
                            <input type="radio" name="visible" value="0" class="text-indigo-600 border-gray-300">
                            <span class="ml-2 text-gray-700">No</span>
                        </label>
                        <label class="flex items-center text-base">
                            <input type="radio" name="visible" value="1" class="text-indigo-600 border-gray-300">
                            <span class="ml-2 text-gray-700">Yes</span>
                        </label>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea name="content" id="content" rows="6" 
                              class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-base p-3"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">
                    <a href="content.php" class="text-sm text-gray-600 hover:underline">Cancel</a>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        Add Page
                    </button>
                </div>
            </form>
        </main>
    </div>

    <?php require("includes/footer.php"); ?>
</body>
</html>
