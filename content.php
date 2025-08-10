<?php require_once("includes/sessions.php"); ?>
<?php
  confirm_logged_in();
?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
if (isset($_GET['subj'])) {
    $sel_subj = $_GET['subj'];
    $sel_page = "";
} else {
    if (isset($_GET['page'])) {
        $sel_page = $_GET['page'];
        $sel_subj = "";
    } else {
        $sel_subj = "";
        $sel_page = "";
    }
}

if ($sel_subj) {
    $sel_subject = get_subject_by_id($sel_subj);
}
if ($sel_page) {
    $sele_page = get_page_by_id($sel_page);
}
?>

<?php include("includes/header.php"); ?>
    <table id="structure" class="w-full border-collapse border border-gray-300">
      <tr>
        <td id="navigation" class="align-top w-1/4 bg-gray-100 p-4 border-r border-gray-300">
          <?php navigation_c($sel_subj, $sel_page); ?>
          <a href="new_subject.php" class="block mt-4 text-blue-600 hover:underline">+ Add a new subject</a>
        </td>
        <td id="page" class="p-6">
          <h2 class="text-2xl font-bold mb-4">
            <?php
              if (isset($sel_subject["menu_name"])) {
                  echo $sel_subject["menu_name"];
              } else if (isset($sele_page["menu_name"])) {
                  echo $sele_page["menu_name"];
              } else {
                  echo "Select an option or add it";
              }
            ?>
          </h2>

          <?php
            if ($sel_subj) {
                echo "<a href='new_page.php?subj=$sel_subj' class='inline-block mb-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600'>Add New Page</a>";
            }
          ?>

          <div class="page_content prose max-w-none">
            <?php
              if (isset($sele_page["content"])) {
                  echo $sele_page["content"];
              }
              if ($sel_page == '7') {
                  echo "<a href='index.php?page=8' class='text-blue-600 hover:underline'>dealer page</a>";
              }
              echo "<br>";
              if ($sel_page) {
                  echo "<a href='edit_page.php?page=$sel_page' class='text-yellow-600 hover:underline'>Edit {$sele_page['menu_name']}</a>";
              }
            ?>
          </div>
        </td>
      </tr>
    </table>
<?php require("includes/footer.php"); ?>
