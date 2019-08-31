
		<link rel = "stylesheet" type = "text/css" href = "/assets/css/footer.css" />
        <script nonce = "<?= $nonce_hash ?>">
               $(document).ready(function() {
               <?php
                if ( !empty($_SESSION['success']) && !empty($_SESSION['message']) ) {?>
                        noty.show("<?= $_SESSION['success'] ?>", "<?= $_SESSION['message'] ?>");
                <?php
                }
                unset($_SESSION['success']);
                unset($_SESSION['message']);?>
           });
      </script>
	</body>
</html>