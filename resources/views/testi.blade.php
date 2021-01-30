<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Varatoimari - Laitetietokanta</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="wrapper flex-grow-1">
    <div class="jumbotron text-center bg-info" style="margin-bottom:0">
      <h1>Varatoimari</h1>
      <p>Laitetietokanta</p> 
    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <a class="navbar-brand" href="#">Tähän käyttäjänimi?</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">Link1</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link2</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link3</a>
          </li>    
        </ul>
      </div>  
    </nav>

    <div class="container" style="margin-top:30px">
      <div class="row">
        <div class="col-sm-4">
          <h2>About Me</h2>
          <h5>Photo of me:</h5>
          
          <p>Some text about me in culpa qui officia deserunt mollit anim..</p>
          <h3>Some Links</h3>
          <p>Lorem ipsum dolor sit ame.</p>
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">Active</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="#">Disabled</a>
            </li>
          </ul>
          <hr class="d-sm-none">
        </div>
        <div class="col-sm-8">
          <h2>TITLE HEADING</h2>
          <h5>Title description, Dec 7, 2017</h5>
          
          <p>Some text..</p>
          <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
          <br>
          <h2>TITLE HEADING</h2>
          <h5>Title description, Sep 2, 2017</h5>
          
          <p>Some text..</p>
          <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
        </div>
      </div>
      <div class="row">
        <div class="w-100 text-center" style="margin-top:10;margin-bottom:10">
          <h2>Tässä alkaa uusi rivi</h2>
        </div>
      </div>
      <div class="row align-content-center">
        <div class="col-4 d-flex flex-wrap" >
          <div class="card card-block text-white bg-danger" style="width: 18rem;">
            <img class="card-img-top p-2" src="https://toppng.com/uploads/preview/angry-birds-pi-c-angry-bird-transparent-background-1156307222480wivux9on.png" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div>
        <div class="col-4 d-flex flex-wrap">
          <div class="card card-block" style="width: 18rem;">
            <div class="card-header">
              Vihainen lintu
            </div>
            <img class="card-img-top" src="https://e7.pngegg.com/pngimages/455/251/png-clipart-bird-beak-angry-bird-black-black-angry-bird-illustration-cartoon-bird.png" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div> 
        <div class="col-4 d-flex flex-wrap" >
          <div class="card card-block text-white bg-danger" style="width: 18rem;">
            <img class="card-img-top p-4" src="https://toppng.com/uploads/preview/angry-birds-pi-c-angry-bird-transparent-background-1156307222480wivux9on.png" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div> 
      </div> {{-- end row --}}
      <div class="content mt-4">
        <div id="example"></div>
      </div>
      
        
    </div>

    <div class="jumbotron text-center bg-dark text-light" style="margin-bottom:0;margin-top:10">
      <p>Footer</p>
      <a href="#" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">Toggle popover</a>
    </div>
  </div>

  <script>
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();   
    });
    
  </script>

  <!-- Scripts -->
  <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
</body>
</html>
