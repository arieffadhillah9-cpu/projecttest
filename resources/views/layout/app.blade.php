<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Film Bioskop</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #111111 !important; 
            color: #e0e0e0; 
        }
        .container, .jumbotron, .bg-dark, .py-5 {
            background-color: transparent !important; 
        }
        .text-black, .text-dark {
            color: #e0e0e0 !important;
        }
        .card {
            background-color: #212121 !important; 
            border: 1px solid #333333;
            color: #e0e0e0;
        }
        .card-header {
            background-color: #1a1a1a !important;
            border-bottom: 1px solid #333333;
            color: #e0e0e0;
        }
        .form-control {
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #555555;
        }
        .form-control:focus {
            background-color: #444444;
            color: #ffffff;
            border-color: #6c757d;
        }
        .btn-default {
            background-color: #343a40;
            color: #e0e0e0;
            border: 1px solid #343a40;
        }
        .btn-default:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .alert-warning {
             background-color: #493b13 !important;
             border-color: #55441a !important;
             color: #ffc107 !important;
        }
        /* Tambahan untuk show.blade.php */
        .movie-header {
            background: linear-gradient(rgba(17, 17, 17, 0.7), rgba(17, 17, 17, 1)), url('{{ $film->poster_path ?? 'placeholder.jpg' }}') no-repeat center center; /* Placeholder akan digunakan jika $film belum didefinisikan */
            background-size: cover;
            padding: 80px 0 30px 0;
            margin-bottom: 30px;
        }
        .detail-card-title {
            color: #aaaaaa;
            font-size: 0.9rem;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .detail-card-value {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

</body>
</html>