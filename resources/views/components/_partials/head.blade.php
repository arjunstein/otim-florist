<meta charset="utf-8">
{{-- <title>{{ $title }}</title> --}}
<meta content="width=device-width, initial-scale=1.0" name="viewport">
{!! SEO::generate() !!}

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap"
    rel="stylesheet">

<!-- Icon Font Stylesheet -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="{{ asset('lib/lightbox/css/lightbox.min.css?v=2.0.0') }}" rel="stylesheet">
<link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css?v=2.0.0') }}" rel="stylesheet">

<!-- Customized Bootstrap Stylesheet -->
<link href="{{ asset('css/bootstrap.min.css?v=' . date('dmYHi')) }}" rel="stylesheet">

<!-- Template Stylesheet -->
<link href="{{ asset('css/style.css?v=' . date('dmYHi')) }}" rel="stylesheet">

{{-- Favicon --}}
<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">


{{-- Vite development --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
