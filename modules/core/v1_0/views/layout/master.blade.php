<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{$title}}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!--common css & js-->
  <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300,400italic' rel='stylesheet' type='text/css'>
  {{HTML::style('assets/core/bootstrap.min.css')}}
  {{HTML::style('assets/core/style.css')}}
  {{HTML::style('assets/core/font-awesome.css')}}

  {{HTML::script('assets/core/jquery.min.js')}}
  {{HTML::script('assets/core/bootstrap.min.js')}}
  <!--common css & js-->

  <!--common backend css & js-->
  @yield('common_backend')
  <!--/common backend css & js-->

  <!--page specific css & js-->
  @yield('page_specific')
  <!--page specific css & js-->


  

  <?php
  $url = URL::current();
  $base = URL::to('/');

  $uri = str_replace($base, "", $url);
  $body_class = str_replace('/', " ", $uri);
  $body_class = trim($body_class);

        if($body_class == "")
          {
            $body_class = "home";
          }
  ?>

</head>
<body class="{{$body_class}}">

  <!--content part-->
  @yield('content')
  <!--content part-->
  

{{HTML::script('assets/core/common.js')}}
</body>
</html>