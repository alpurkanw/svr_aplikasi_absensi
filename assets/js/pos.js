$(document).ready(function () {
	let cart = [];

	function refreshCart() {
		$("#cart-list").empty();
		cart.forEach((item, i) => {
			$("#cart-list")
				.append(`<li class="list-group-item d-flex justify-content-between">
                ${item.nama} x${item.qty}
                <span>Rp ${item.harga * item.qty}</span>
            </li>`);
		});
	}

	$(".menu-btn").click(function () {
		let id = $(this).data("id");
		let nama = $(this).data("nama");
		let harga = $(this).data("harga");

		let existing = cart.find((i) => i.id === id);
		if (existing) {
			existing.qty += 1;
		} else {
			cart.push({ id, nama, harga, qty: 1 });
		}

		refreshCart();
	});

	$("#checkout-btn").click(function () {
		alert("Fitur pembayaran akan dikembangkan selanjutnya.");
	});
});
