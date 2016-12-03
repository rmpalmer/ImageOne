<?php
include 'view/header.php'; ?>
<main>
    <h1>DEBUG</h1>
    <?php echo $_FILES['userfile']['name'];?>
    <?php echo $_FILES['userfile']['tmp_name'];?>
</main>
<?php include 'view/footer.php'; ?>