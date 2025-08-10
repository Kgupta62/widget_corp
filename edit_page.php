<?php require_once("includes/sessions.php"); ?>
<?php confirm_logged_in(); ?>
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
        echo "<div class='bg-red-100 text-red-700 p-4 rounded-md mb-4'>";
        echo "<p>Please review the following fields:</p><ul class='list-disc pl-6'>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul></div>";
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

<!-- Tailwind container -->
<div class="flex gap-6 p-6">
    <!-- Navigation -->
    <div class="w-1/4 bg-gray-100 p-4 rounded-lg shadow">
        <?php navigation($sel_subj, $sel_page); ?>
    </div>

    <!-- Page content -->
    <div class="w-3/4 bg-white p-6 rounded-lg shadow">
        <?php if (isset($message) && $message != ""): ?>
            <div class="mb-4 p-3 rounded-md 
                <?php echo (strpos($message, 'successfully') !== false) 
                    ? 'bg-green-100 text-green-700' 
                    : 'bg-red-100 text-red-700'; ?>">
                <?php echo $message; $message = ""; ?>
            </div>
        <?php endif; ?>

        <h2 class="text-2xl font-bold mb-4">Edit Page: <?php echo $sel_page_data['menu_name']; ?></h2>

        <form method="post" action="edit_page.php?page=<?php echo urlencode($sel_page); ?>" class="space-y-4">
            <div>
                <label for="menu_name" class="block text-sm font-medium text-gray-700">Page Name:</label>
                <input type="text" name="menu_name" id="menu_name" 
                       value="<?php echo $sel_page_data['menu_name']; ?>"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Position:</label>
                <select name="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2">
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
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Visible:</label>
                <div class="mt-1 flex gap-4">
                    <label class="inline-flex items-center">
                        <input type='radio' name='visible' value='0' class="form-radio text-blue-600"
                            <?php if ($sel_page_data['visible'] == '0') echo "checked"; ?>> 
                        <span class="ml-2">No</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type='radio' name='visible' value='1' class="form-radio text-blue-600"
                            <?php if ($sel_page_data['visible'] == '1') echo "checked"; ?>> 
                        <span class="ml-2">Yes</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content:</label>
                <textarea name="content" rows="5" id="content"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2"><?php echo $sel_page_data['content']; ?></textarea>
            </div>

            <div class="flex items-center gap-4">
                <input type="submit" name="submit" value="Edit Page" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow cursor-pointer">
                <a href="delete_page.php?page=<?php echo urlencode($sel_page); ?>" 
                   onclick="return confirm('Are you sure?');"
                   class="text-red-600 hover:underline">Delete Page</a>
                <a href="content.php" class="text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require("includes/footer.php"); ?>
