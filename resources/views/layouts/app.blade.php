<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')

</head>
<body id="app-layout">

    @include('layouts.partials.nav')

    @include('layouts.partials.flash')

    @yield('content')

    @include('layouts.partials.javascript')

    @yield('script')

</body>
</html>
