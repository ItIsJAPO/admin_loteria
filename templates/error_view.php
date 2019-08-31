<?php

    use util\token\TokenHelper;

    $nonce_hash = TokenHelper::generarCodigo(mt_rand());

    include 'incl/top.php';

?>


<div class="container-fluid">
    <div class = "text-center">
    	<div style = "margin-bottom: 30px">
    		<i class = "fas fa-5x fa-times-circle red-color"></i>
    	</div>

    	<div style = "margin-bottom: 30px">
    		<h1 class = "red-color"> <b>ERROR </b> </h1>
    	</div>

    	<div style = "margin-bottom: 30px">
    		<h2> <b> Ha ocurrido un error</b> </h2>
    	</div>
		
		<div style = "margin-bottom: 30px">
			<p class = "black-color" style = "font-weight: 400"> <?= $dataAndView->getData('message') ?> </p>
		</div>

		<a href = "#" class = "btn btn-warning btn-rounded waves-effect waves-light m-b-40" id = "back_button"> Regresar </a>
	</div>
</div>

<script nonce = "<?= $nonce_hash ?>">
	$(document).ready(function() {
        $("#back_button").on("click", function() {
            window.history.back();
        });
	});
</script>

<?php include 'incl/footer.php'; ?>