<!DOCTYPE html>
<html>
<head>
	<title>Add Products</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<style>
		.add-product-container {
			width:  700px;
			margin:  100px auto;
			padding:  10px;
			background: white;
			padding: 20px;
			border-bottom: 2px solid #ccc;
		}

		.add-product-form {
			display: flex;
			flex-direction: column;
			gap:  20px;
		}

		body {
			background: #f3f3f3;
		}


		.btn-success {
			background: #37bc7e;
			border-radius: 0;
			border: 0;
			border-bottom: 3px solid #2b9a66;
		}

		.btn-primary {
			border-radius: 0;
			border: 0;
			border-bottom: 3px solid #2663BC;
		}

		 .toastify-top {
            text-transform: uppercase;
            font-weight: bold;
        }
	</style>
</head>
<body>
	<div class="add-product-container">
		<form class="add-product-form">
			<a href='{{url("/shop")}}'>
			  <button type="button" class="btn btn-success">BUY PRODUCTS</button> 
			</a>
			  <div class="form-group">
			    <label for="product-name">Product Name</label>
			    <input type="text" name="name" class="form-control" id="product-name">
			  </div>
			  <div class="form-group">
			    <label for="product-price">Price (USD)</label>
			    <input type="number" name="price" class="form-control" id="product-price">
			  </div>
			  <div class="form-group">
			    <label for="product-stock">Stock</label>
			    <input type="number" name="stock" class="form-control" id="product-stock">
			  </div>
			  <button type="button" class="btn btn-primary add-btn">ADD PRODUCT</button> 
		</form>
	</div>


	<script>

		$(document).ready(() => {

			let isError = 0;

			$(".add-btn").on("click",() => {

				if($("#product-name").val() == "" || $("#product-price").val() == "" || $("#product-stock").val() == ""){
					showToastify("Values cannot be null");
					isError = 1;
				}

				var Regex='/^[^a-zA-Z]*$/';	
				if (!$("#product-name").val().match("^[a-zA-Z]+$") && $("#product-name").val() != "") {
					showToastify("Product name cannot be a number");
					isError = 1;
				}

				if(isError == 0) {
					$.ajax({
						url: "{{route("storeProducts")}}",
						type: "POST",
						data : {
							name: $("#product-name").val(),
							price: $("#product-price").val(),
							stock: $("#product-stock").val()
						},
						success: () => {
							showToastify("Product added successfully","#04AA6D");
						}
					});
				}

				

			});

		});

		function showToastify(msg,color="#dc3545") {
	        Toastify({
	                text: msg,
	                duration: 2000,
	                destination: "#",
	                newWindow: true,
	                close: true,
	                gravity: "top",
	                position: "right",
	                stopOnFocus: true,
	                style: {
	                    background: color,
	                },
	                onClick: function(){}
	            }).showToast();
        }	


	</script>
</body>
</html>