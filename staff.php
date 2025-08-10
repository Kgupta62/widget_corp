<?php require_once("includes/sessions.php"); ?>
<?php
  confirm_logged_in();
?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<!-- Staff Menu Section -->
<div class="max-w-7xl mx-auto px-4 py-8">
  
  <div class="bg-white shadow-lg rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200">
      <h2 class="text-2xl font-bold text-rose-600">Staff Menu</h2>
      <p class="text-slate-500 mt-1">
        Welcome to the Staff area, 
        <span class="font-semibold text-slate-800">
          <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>
      </p>
    </div>

    <div class="px-6 py-4">
      <ul class="space-y-3">
        <li>
          <a href="content.php" 
             class="block px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition">
             📄 Manage Website Content
          </a>
        </li>
        <li>
          <a href="new_user.php" 
             class="block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
             ➕ Add New User
          </a>
        </li>
        <li>
          <a href="logout.php" 
             class="block px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition">
             🚪 Logout
          </a>
        </li>
      </ul>
    </div>
  </div>

</div>

<?php include("includes/footer.php"); ?>
