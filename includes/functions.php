<?php
function confirm_query($result_set)
{
    if (!$result_set) {
        die("Database query failed: " . mysqli_error($GLOBALS['connection']));
    }
}

function get_all_subjects($public = true)
{
    $query = "SELECT * FROM subjects";
    if ($public) {
        $query .= " WHERE visible = 1";
    }
    $query .= " ORDER BY position";
    $subject_set = mysqli_query($GLOBALS['connection'], $query);
    confirm_query($subject_set);
    return $subject_set;
}

function get_subject_by_id($subject_id)
{
    $query = "SELECT * FROM subjects WHERE id = {$subject_id} LIMIT 1";
    $sub_id = mysqli_query($GLOBALS['connection'], $query);
    confirm_query($sub_id);
    $subject = mysqli_fetch_array($sub_id);
    return $subject;
}

function get_page_by_id($page_id)
{
    $query = "SELECT * FROM pages WHERE id = {$page_id} LIMIT 1";
    $page_res = mysqli_query($GLOBALS['connection'], $query);
    confirm_query($page_res);
    $page = mysqli_fetch_array($page_res);
    return $page;
}

function navigation($sel_subj, $sel_page)
{
    echo "<ul class='space-y-2'>";
    $subject_set = get_all_subjects(false);
    while ($subject = mysqli_fetch_array($subject_set)) {
        $selectedClass = ($sel_subj == $subject['id']) ? "bg-blue-600 text-white rounded-lg" : "bg-gray-100 hover:bg-blue-100 rounded-lg";
        echo "<li class='{$selectedClass} transition p-2'>";
        echo "<a class='block font-semibold' href='edit_subject.php?subj=" . urlencode($subject["id"]) . "'>" . htmlspecialchars($subject["menu_name"]) . "</a></li>";

        $page_set = mysqli_query($GLOBALS['connection'], "SELECT * FROM pages WHERE subject_id = {$subject['id']} ORDER BY position");
        confirm_query($page_set);

        echo "<ul class='ml-6 space-y-1'>";
        while ($page = mysqli_fetch_array($page_set)) {
            $pageSelectedClass = ($sel_page == $page['id']) ? "text-blue-600 font-medium" : "text-gray-700 hover:text-blue-500";
            echo "<li><a class='{$pageSelectedClass}' href='content.php?page=" . urlencode($page["id"]) . "'>" . htmlspecialchars($page["menu_name"]) . "</a></li>";
        }
        echo "</ul>";
    }
    echo "</ul>";
}

function navigation_c($sel_subj, $sel_page)
{
    echo "<ul class='space-y-2'>";
    $subject_set = get_all_subjects(false);
    while ($subject = mysqli_fetch_array($subject_set)) {
        $selectedClass = ($sel_subj == $subject['id']) ? "bg-blue-600 text-white rounded-lg" : "bg-gray-100 hover:bg-blue-100 rounded-lg";
        echo "<li class='{$selectedClass} transition p-2'>";
        echo "<a class='block font-semibold' href='content.php?subj=" . urlencode($subject["id"]) . "'>" . htmlspecialchars($subject["menu_name"]) . "</a></li>";

        $page_set = mysqli_query($GLOBALS['connection'], "SELECT * FROM pages WHERE subject_id = {$subject['id']} ORDER BY position");
        confirm_query($page_set);

        echo "<ul class='ml-6 space-y-1'>";
        while ($page = mysqli_fetch_array($page_set)) {
            $pageSelectedClass = ($sel_page == $page['id']) ? "text-blue-600 font-medium" : "text-gray-700 hover:text-blue-500";
            echo "<li><a class='{$pageSelectedClass}' href='content.php?page=" . urlencode($page["id"]) . "'>" . htmlspecialchars($page["menu_name"]) . "</a></li>";
        }
        echo "</ul>";
    }
    echo "</ul>";
}

function navigation_i($sel_subj, $sel_page)
{
    $x = "";
    echo "<ul class='space-y-2'>";
    $subject_set = get_all_subjects(true);
    while ($subject = mysqli_fetch_array($subject_set)) {
        if ($sel_subj == $subject["id"]) {
            echo "<li class='bg-blue-600 text-white rounded-lg p-2'>";
            echo "<a class='block font-semibold' href='index.php?subj=" . urlencode($subject["id"]) . "'>" . htmlspecialchars($subject["menu_name"]) . "</a></li>";

            $ques = "SELECT * FROM pages WHERE subject_id = {$subject['id']} AND visible = '1' ORDER BY position";
            $page_set = mysqli_query($GLOBALS['connection'], $ques);
            confirm_query($page_set);

            echo "<ul class='ml-6 space-y-1'>";
            while ($page = mysqli_fetch_array($page_set)) {
                if (!$sel_page) {
                    if ($page["position"] == '1') {
                        $x = $page["id"];
                        echo "<li class='text-blue-600 font-medium'><a href='index.php?page=" . urlencode($page["id"]) . "'>" . htmlspecialchars($page["menu_name"]) . "</a></li>";
                    } else {
                        echo "<li><a class='text-gray-700 hover:text-blue-500' href='index.php?page=" . urlencode($page["id"]) . "'>" . htmlspecialchars($page["menu_name"]) . "</a></li>";
                    }
                } else {
                    $pageClass = ($sel_page == $page['id']) ? "text-blue-600 font-medium" : "text-gray-700 hover:text-blue-500";
                    echo "<li><a class='{$pageClass}' href='index.php?page=" . urlencode($page["id"]) . "'>" . htmlspecialchars($page["menu_name"]) . "</a></li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<li class='bg-gray-100 hover:bg-blue-100 rounded-lg p-2'><a class='block font-semibold' href='index.php?subj=" . urlencode($subject["id"]) . "'>" . htmlspecialchars($subject["menu_name"]) . "</a></li>";
        }
    }
    echo "</ul>";
    return $x;
}

function get_pages_for_subject($sub, $public = true)
{
    $query = "SELECT * FROM pages WHERE subject_id = {$sub}";
    if ($public) {
        $query .= " AND visible = '1'";
    }
    $query .= " ORDER BY position ASC";
    $res = mysqli_query($GLOBALS['connection'], $query);
    confirm_query($res);
    return $res;
}
?>
