<div class="container text-center p-5">
    <h1>Page you are looking for does not exist</h1>
    <p>you are being redirected to home</p>
    <p class="fs-1">
        <?php require "./modules/loading.php" ?>
    </p>
</div>

<script>
    setTimeout(function () {
        window.location.href = "?page=home";
    }, 3000); 
</script>