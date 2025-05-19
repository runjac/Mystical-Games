$(document).ready(function(){
	getItems();
});

function getItems(){
	$.ajax({
		url: 'ajax-carrito.php',
		method: 'get',
		data: {accion: 'get'},
		success: function(data){
			var carrito = JSON.parse(data);
			var html = '';
			var subtotal = 0;
			var total = 0;

			carrito.forEach(function(item){
				subtotal = item.precio * item.cantidad;
				total += subtotal;

				html += `
					<div class="pedido bg-dark p-3 rounded-3 mb-2">
					     <div class="pedido-img">
					         <img src="${item.img}" alt="">
					     </div>
					     <div class="pedido-nombre p-1">
					         <h6 class="text-light roboto-bold ">${item.titulo}</h6>
					         <p class="text-light roboto-regular fs-6"> $ ${subtotal}</p>  
					     </div>
					     <div class="pedido-cantidad d-flex justify-content-center align-items-center">
					         <select class="form-select p-3 select-widt update-carrito" aria-label="Default select example" data-id="${item.id}">
					         	<option value="${item.cantidad}">${item.cantidad}</option>
					             <option value="1">1</option>
					             <option value="2">2</option>
					             <option value="3">3</option>
					             <option value="4">4</option>
					             <option value="5">5</option>
					             <option value="6">6</option>
					             <option value="7">7</option>
					             <option value="8">8</option>
					             <option value="9">9</option>
					             <option value="10">10</option>
					             <option value="11">11</option>
					             <option value="12">12</option>
					             <option value="13">13</option>
					             <option value="14">14</option>
					             <option value="15">15</option>
					             <option value="16">16</option>
					             <option value="17">17</option>
					             <option value="18">18</option>
					             <option value="19">19</option>
					             <option value="20">20</option>
					         </select>
					         <a href="javascript:void(0)" class="delete-carrito" data-id="${item.id}">
					             <i class="fa-solid fa-trash  p-1 m-1" style="color:#555555"></i>
					         </a>
					     </div>
					</div>
				`;
			});

			$('#carrito-items').html(html);
			$('#count-carrito').text(carrito.length);
			$('#total-carrito').text(total);
		}
	});
}

$('.add-carrito').click(function(){
	var id = $(this).data('id');

	var cantidad = 1;

	if($('#number-display').length){
		cantidad = $('#number-display').text();
	}
	
	console.log(id);

	$.ajax({
		url: 'ajax-carrito.php',
		method: 'get',
		data: { accion: 'add', id: id, cantidad: cantidad },
		success: function(data){
			data = JSON.parse(data);
			
			if(data.status){
				
				Swal.fire({
					title: 'Mensaje',
					text: 'Producto agregado al carrito',
					icon: 'success',
					toast: true,
					position: 'bottom-end'

				});

				getItems();
			}else{
				Swal.fire({
					title: 'Error',
					text: data.error,
					icon: 'error',
					toast: true,
					position: 'bottom-end'

				});
			}
		}
	});

});

$(document).on('click', '.delete-carrito', function(){
	var id = $(this).data('id');

	console.log(id);

	Swal.fire({
		title: "¿Desea eliminar el producto?",
		showDenyButton: true,
		confirmButtonText: 'Si',
		denyButtonText: 'No',
		toast: true,
		position: 'bottom-end'
	}).then((result) => {
	  
	  if (result.isConfirmed) {

	  	$.ajax({
	  		url: 'ajax-carrito.php',
	  		method: 'get',
	  		data: { accion: 'delete', id: id },
	  		success: function(data){
	  			getItems();
	  			if(location.href.includes('carrito.php') || location.href.includes('comprar.php')){
	  				location.reload();
	  			}
	  			
	  		}
	  	});
	    
	  }

	});

});

$(document).on('change', '.update-carrito', function(){
	var cantidad = $(this).val();
	var id = $(this).data('id');

	$.ajax({
		url: 'ajax-carrito.php',
		method: 'get',
		data: { accion: 'update', id: id, cantidad: cantidad },
		success: function(data){
			data = JSON.parse(data);
			
			if(data.status){
				
				Swal.fire({
					title: 'Mensaje',
					text: 'Producto actualizado',
					icon: 'success',
					toast: true,
					position: 'bottom-end'

				});

				getItems();

				if(location.href.includes('carrito.php') || location.href.includes('comprar.php')){
	  				location.reload();
	  			}
	  			
			}else{
				Swal.fire({
					title: 'Error',
					text: data.error,
					icon: 'error',
					toast: true,
					position: 'bottom-end'

				});
			}
		}
	});
});

