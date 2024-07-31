<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script data-navigate-once src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

<!-- Template Javascript -->
<script src="{{ asset('js/main.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const whatsappButtons = document.querySelectorAll('.whatsapp-btn');

    whatsappButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productLink = this.getAttribute('data-product-link');
            const phoneNumber = '6281808881477'; // Ganti dengan nomor WhatsApp Anda

            const message = `Halo, saya ingin memesan produk "${productName} ${productId}". Berikut link produknya: ${productLink}, mohon dibantu prosesnya`;

            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

            window.open(whatsappUrl, '_blank');
        });
    });
});
</script>
