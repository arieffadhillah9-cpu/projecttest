<style>
        html,body {
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