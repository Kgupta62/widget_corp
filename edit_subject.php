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

<div class="flex gap-6 p-6">
    <div class="w-1/4 bg-gray-100 p-4 rounded-lg shadow">
        <?php navigation($sel_subj, $sel_page); ?>
    </div>

    <div class="flex-1 bg-white p-6 rounded-lg shadow">
        <?php if (!empty($message)): ?>
            <div class="mb-4 p-3 rounded text-white <?php echo empty($errors) ? 'bg-green-500' : 'bg-red-500'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($sel_subject)) { ?>
            <h2 class="text-2xl font-bold mb-4">
                Edit Subject: <?php echo htmlentities($sel_subject['menu_name']); ?>
            </h2>

            <form method="post" action="edit_subject.php?subj=<?php echo urlencode($sel_subj); ?>" class="space-y-4">
                <div>
                    <label class="block font-medium mb-1">Subject Name:</label>
                    <input type="text" name="menu_name" id="menu_name"
                           value="<?php echo htmlentities($sel_subject['menu_name']); ?>"
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Position:</label>
                    <select name="position"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                </div>

                <div>
                    <label class="block font-medium mb-1">Visible:</label>
                    <label class="mr-4">
                        <input type="radio" name="visible" value="0" <?php if ($sel_subject['visible'] == 0) echo "checked"; ?>>
                        No
                    </label>
                    <label>
                        <input type="radio" name="visible" value="1" <?php if ($sel_subject['visible'] == 1) echo "checked"; ?>>
                        Yes
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Subject</button>
                    <a href="delete_subject.php?subj=<?php echo urlencode($sel_subj); ?>"
                       onclick="return confirm('Are you sure?');"
                       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete Subject</a>
                </div>
            </form>

            <br>
            <a href="content.php" class="text-blue-500 hover:underline">Cancel</a>
        <?php } else { ?>
            <p class="text-gray-500">Subject not found.</p>
        <?php } ?>
    </div>
</div>

<?php require("includes/footer.php"); ?>
