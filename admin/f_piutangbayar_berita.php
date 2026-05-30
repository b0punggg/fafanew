<div id="snackbar" style="z-index: 1"></div>
<?php
include 'strating.php';
$berita=$_POST['berita'];
?>
<script>
popnew_warning(<?=$berita?>);
</script>