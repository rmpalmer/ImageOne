<?php
include 'view/header.php'; ?>
<main>
    <h1>DEBUG</h1>
    
    <?php echo $imageName?>
    <?php echo $fileSize?>
    <?php echo $tmpFilePath?>
    <?php echo $keywords?>
    <?php print_r($foo);?>
    <p> <a href="index.php">Try Again</a></p>
</main>
<?php include 'view/footer.php'; ?>