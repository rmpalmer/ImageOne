<?php
include 'view/header.php'; ?>
<main>
    <h1>DEBUG</h1>

	<?php echo $_SESSION['limit_offset'];?>
	<?php echo $_SESSION['limit_count'];?>
	<?php echo $_SESSION['filter_keys']?>
    
    <p> <a href="index.php">Try Again</a></p>
</main>
<?php include 'view/footer.php'; ?>