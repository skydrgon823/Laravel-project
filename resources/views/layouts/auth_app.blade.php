<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>whatsjob Dashboard</title>
    <!-- StyleSheets  -->
 <!--    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/css/intlInputPhone.min.css') }}">     --> 
    <link rel="stylesheet" href="{{ asset('admin/css/dashlite.css?ver=2.0.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/css/theme.css?ver=2.0.0') }}">

</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                @yield('content')

                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('admin/js/bundle.js?ver=2.0.0') }}"></script>
    <script src="{{ asset('admin/js/scripts.js?ver=2.0.0') }}"></script>
    <script src="{{ asset('admin/js/charts/gd-default.js?ver=2.0.0') }}"></script>
    <script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
    <script src="{{ asset('admin/js/intlInputPhone.min.js') }}"></script>

      <script>
            $(document).ready(function() {
            $('.input-phone').intlInputPhone();
        })
    </script>
 
</body>

</html>