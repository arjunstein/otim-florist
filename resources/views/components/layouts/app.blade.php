<!DOCTYPE html>
<html lang="en {{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components._partials.head')
</head>

<body>

    <!-- Navbar start -->
    @include('components._partials.navbar')
    <!-- Navbar End -->

    {{ $slot }}

    <!-- Footer Start -->
    @include('components._partials.footer')
    <!-- Footer End -->

    <!-- Copyright Start -->
    @include('components._partials.copyright')
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i
            class="fa fa-arrow-up"></i></a>

    @include('components._partials.scripts')

</body>

</html>
