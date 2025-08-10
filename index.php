<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
$sel_subj = "";
$sel_page = "";
if (isset($_GET['subj'])) {
    $sel_subj = $_GET['subj'];
}

if (isset($_GET['page'])) {
    $sel_page = $_GET['page'];
}

if ($sel_subj) {
    $sel_subject = get_subject_by_id($sel_subj);
}
if ($sel_page) {
    $sele_page = get_page_by_id($sel_page);
}
?>

<?php include("includes/header.php"); ?>

<div class="flex min-h-screen bg-gray-50">
    <!-- Navigation Sidebar -->
    <aside class="w-1/4 bg-white border-r p-4 shadow-md">
        <?php
        if (!$sel_page) {
            $x = navigation_i($sel_subj, $sel_page);
        } else {
            $x = navigation_i($sele_page['subject_id'], $sel_page);
        }
        ?>
    </aside>

    <!-- Page Content -->
    <main class="flex-1 p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            <?php
            if ($x) {
                $sel_page = $x;
                $sele_page = get_page_by_id($sel_page);
            }
            if (isset($sele_page["menu_name"])) {
                echo htmlentities($sele_page["menu_name"]);
            } else {
                echo "Welcome to Widget Corp";
            }
            ?>
        </h2>

        <div class="prose max-w-none">
            <?php
            if (isset($sele_page["content"])) {
                echo nl2br($sele_page["content"]);
            }
            if ($sel_page == '7') {
                echo '<a href="index.php?page=8" class="text-blue-600 hover:underline">Dealer Page</a>';
            }
            ?>
            <br>
            <span class="text-gray-600">Already a user?</span>
            <a href="login.php" class="text-blue-600 hover:underline">Login</a>
        </div>
    </main>
</div>

<?php require("includes/footer.php"); ?>
