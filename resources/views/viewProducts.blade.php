<!DOCTYPE html>
<html>
<head>
	<title>Products</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
	<style>

		body {
			background: #f3f3f3;
		}

		.main-container {
			display:  flex;
			justify-content: space-evenly;
			margin: 40px auto;
		}

		.show-error,.add-products,.add-success{
			width:  700px;
			margin: 40px auto;
		}

		.buy-products-container {
			background: white;
			padding: 20px;
			border-bottom: 2px solid #ccc;
		}

		.cart-container {
			background: white;
			padding: 20px;
			border-bottom: 2px solid #ccc;
		}

		.btn-success {
			background: #37bc7e;
			border-radius: 0;
			border: 0;
			border-bottom: 3px solid #2b9a66;
		}

		.btn-danger {
			border-radius: 0;
			border: 0;
			border-bottom: 3px solid #b82c39;
		}

		tfoot {
			background: tomato;
			color:  white;
		}

		.toastify-top {
            text-transform: uppercase;
            font-weight: bold;
        }
	</style>
</head>
<body>

	<div class="main-container">
		<div class="buy-products-container">
			<h3>Product List</h3>
			<a href="/">
				<button type="button" class="btn btn-success ">ADD PRODUCTS</button> 
			</a>
			<table class="table table-striped">
			  <thead class="">
			    <tr>
			      <th scope="col">Item Name</th>
			      <th scope="col">Item Price</th>
			      <th scope="col">Item Quantity</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>

	    		@foreach($products as $product)
		    		<tr class="{{'product-'.$product->ID}}">
				      <td>{{$product->Name}}</td>
				      <td>USD {{$product->Price}}</td>
				      <td>
				      	<input type="number" class="form-control {{'product-qty-'.$product->ID}} product-qty" max="{{$product->Stock}}" min="1" value="{{$product->Stock}}">
				      </td>
				      <td>
				      	<button type="button" data-product="{{$product->ID}}" data-stock="{{$product->Stock}}" class="btn btn-success add-cart">ADD TO CART</button> 
				      </td>
				     </tr>
			    @endforeach		    
				
			  </tbody>
			</table>
		</div>

		<div class="cart-container">
			<h3>Shopping Cart</h3>
			<table class="table table-striped">
			  <thead>
			    <tr>
			      <th scope="col">Item Name</th>
			      <th scope="col">Price</th>
			      <th scope="col">Quantity</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>

	    		@foreach($cartData as $item)
		    		<tr class="{{'cart-'.$item->ID}}">
				      <td>{{$item->Name}}</td>
				      <td>USD {{$item->ItemTotalPrice}}</td>
				      <td>
				      	{{$item->Quantity}}
				      </td>
				      <td>
				      	<button type="button" data-product="{{$item->ProductID}}" data-cart="{{$item->ID}}" class="btn btn-danger delete-cart">DELETE</button> 
				      </td>
				     </tr>
			    @endforeach		    
			  </tbody>
			  <tfoot>
			  	<tr>
					<td colspan="4" class="total-price">Total Cost : USD <b>{{$totalPrice}}</b></td>
				</tr>
			  </tfoot>
			</table>
		</div>
	</div>

	


	<script>

		$(".buy-products-container .add-cart").on("click",function() {
			let product = $(this).data("product");
			let stock = $(this).data("stock");
			let qty = $(".product-qty-"+product).val();

			if(qty <= 0){
				showToastify("Quantity cannot be zero");
			}else if(qty > stock){
				showToastify("We do not have this amount of quantity in stock");
			}	
			else{

				$.ajax({
					url: "{{route('addToCart')}}",
					type: "POST",
					data: {
						product,
						qty,
						stock
					},
					success: function(data) {
						if(data == "-1"){
							showToastify("We do not have this amount of quantity in stock");
						}else{
							showToastify("Product has been added to cart","#04AA6D");
							$(".buy-products-container tbody").html(makeNewList(data));
							$(".cart-container tbody").html(makeCartList(data));
							$(".total-price").html("Total Cost : USD <b>"+data.totalPrice+"</b>");
							attachClick();
						}
						
					}
				});
			}
		});

			$(".delete-cart").on("click",function() {
				let product = $(this).data("product");
				let cart = $(this).data("cart");
				
				$.ajax({
					url: "{{route('deleteCartItem')}}",
					type: "POST",
					data: {
						product,
						cart,
					},
					success: function(data) {

						$.ajax({
							url: "{{route('getProducts')}}",
							type: "GET",
							success: function(data) {
								$(".buy-products-container tbody").html(makeNewList(data));
								attachClick();
							}
						});

						showToastify("Product has been removed from cart","#04AA6D");
						$(".cart-"+cart).hide();
						$(".total-price").html("Total Cost : USD <b>"+data+"</b>");
					}
				});
			});

function makeNewList(data) {
	let updatedProductList = "";

	data.products.forEach((item)=>{
		updatedProductList += `<tr class='product-${item.ID}'>
	      <td>${item.Name}</td>
	      <td>USD ${item.Price}</td>
	      <td>
	      	<input type="number" class="form-control product-qty-${item.ID}" max="${item.Stock}" min="1" value="${item.Stock}">
	      </td>
	      <td>
	      	<button type="button" data-product="${item.ID}" data-stock="${item.Stock}" class="btn btn-success add-cart">ADD TO CART</button> 
	      </td>
	    </tr>`
	});

	return updatedProductList;
}

function makeCartList(data) {
	let updatedCartList = "";

	data.cartData.forEach((item)=>{
		updatedCartList += `<tr class="cart-${item.ID}">
	      <td>${item.Name}</td>
	      <td>USD ${item.ItemTotalPrice}</td>
	      <td>
	      	${item.Quantity}
	      </td>
	      <td>
	      	<button type="button" data-product="${item.ProductID}" data-cart="${item.ID}" class="btn btn-danger delete-cart">DELETE</button> 
	      </td>
	     </tr>
	     `
	});

	return updatedCartList;
}

function attachClick() {
	$(".buy-products-container .add-cart").on("click",function() {
			let product = $(this).data("product");
			let stock = $(this).data("stock");
			let qty = $(".product-qty-"+product).val();

			if(qty <= 0){
				showToastify("Quantity cannot be zero");
			}else if(qty > stock){
				showToastify("We do not have this amount of quantity in stock");
			}	
			else{

				$.ajax({
					url: "{{route('addToCart')}}",
					type: "POST",
					data: {
						product,
						qty,
						stock
					},
					success: function(data) {
						if(data == "-1"){
							showToastify("We do not have this amount of quantity in stock");
						}else{
							showToastify("Product has been added to cart","#04AA6D");
							$(".buy-products-container tbody").html(makeNewList(data));
							$(".cart-container tbody").html(makeCartList(data));
							$(".total-price").html("Total Cost : USD <b>"+data.totalPrice+"</b>");
							attachClick();
						}
						
					}
				});
			}
		});

	$(".delete-cart").on("click",function() {
		let product = $(this).data("product");
		let cart = $(this).data("cart");
		
		$.ajax({
			url: "{{route('deleteCartItem')}}",
			type: "POST",
			data: {
				product,
				cart,
			},
			success: function(data) {

				$.ajax({
					url: "{{route('getProducts')}}",
					type: "GET",
					success: function(data) {
						$(".buy-products-container tbody").html(makeNewList(data));
						attachClick();
					}
				});

				showToastify("Product has been removed from cart","#04AA6D");
				$(".cart-"+cart).hide();
				$(".total-price").html("Total Cost : USD <b>"+data+"</b>");
			}
		});
	});
}

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