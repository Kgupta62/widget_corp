</div>
<div id="footer">Copyright 2017</div>
</div>
</body>

<!-- In includes/header.php, inside <head> -->
<script src="https://cdn.tailwindcss.com"></script>

</html>
<?php
  if (isset($connection)) {
      mysqli_close($connection);
  }
?>
