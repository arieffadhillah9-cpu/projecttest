@extends('layout.app')

{{-- Menambahkan custom style untuk background lembut seperti referensi --}}
@section('styles')

@endsection

@section('content')
<div class="container-fluid p-0">
        <div class="p-5 text-white" 
        style="background-image: url({{ asset('adminlte/assets/dist/img/cc.png') }}); 
        background-size: cover; 
        background-position: center; 
        position: top; 
        height: 100%;
        width: 100%;">
            <div style="position: absolute; top: 0; left: 0; width: 60%; height: 100%; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);"></div>

            <div class="row" style="position: relative; z-index: 1;">
                <div class="col-md-6">
                    <p class="mt-5 mb-0 text-right"><small>2025.</small></p>
                    <h1 class="display-4 font-weight-bold">Chainsaw Man: Reze Arc</h1>
                    <p class="lead">Our time on earth has come to an end research team takes on the most important mission in the history of mankind: traveling beyond our galaxy find if humanity has a future among the stars.</p>
                    <button class="btn btn-danger btn-lg"><i class="fas fa-play mr-2"></i> Watch trailer</button>
                </div>
            </div>
            </div>

        <div class="card card-dark rounded-0 mb-0" style="background-color: #1a1a1a;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="text-white">CHOSSE DATE</label>
                        <div class="d-flex text-white">
                            <a href="#" class="text-white mr-3">MON <span class="badge bg-danger">30</span></a>
                            <a href="#" class="text-white mr-3">TUE</a>
                            <a href="#" class="text-white mr-3">WED</a>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="text-white">CHOSSE TIME</label>
                        <div class="d-flex text-white">
                            <a href="#" class="btn btn-outline-light btn-sm mr-2">15:00</a>
                            <a href="#" class="btn btn-outline-light btn-sm mr-2">17:00</a>
                            <a href="#" class="btn btn-outline-light btn-sm mr-2">19:00</a>
                            <a href="#" class="btn btn-danger btn-sm">21:00</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-danger btn-lg">Buy ticket</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="content" style="background-color: #000000; padding-top: 20px; padding-bottom: 50px;">
    <div class="container">
        <div class="row mb-4 text-white align-items-center">
            <div class="col-md-8 d-flex">
                <a href="#" class="text-white mr-3 border-right pr-3">All movies</a>
                <a href="#" class="text-white mr-3 border-right pr-3">By Date <i class="fas fa-caret-down ml-1"></i></a>
                <a href="#" class="text-white mr-3 border-right pr-3">By Category <i class="fas fa-caret-down ml-1"></i></a>
                <a href="#" class="text-danger">Coming soon</a>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search movie" style="background-color: #333333; border: none; color: white;">
                    <div class="input-group-append">
                        <span class="input-group-text bg-danger border-0"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card bg-dark border-0 text-white">
                    <img src="path/to/mummy_poster.jpg" class="card-img-top rounded-0" alt="Mummy">
                    <div class="card-body p-2">
                        <h5 class="card-title font-weight-bold mb-1">Mummy</h5>
                        <div class="d-flex flex-wrap">
                            <small class="mr-2">15:00</small>
                            <small class="mr-2">17:00</small>
                            <small class="mr-2">19:00</small>
                            <small class="text-danger">21:00</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card bg-dark border-0 text-white">
                    <img src="path/to/mummy_poster.jpg" class="card-img-top rounded-0" alt="Mummy">
                    <div class="card-body p-2">
                        <h5 class="card-title font-weight-bold mb-1">Mummy</h5>
                        <div class="d-flex flex-wrap">
                            <small class="mr-2">15:00</small>
                            <small class="mr-2">17:00</small>
                            <small class="mr-2">19:00</small>
                            <small class="text-danger">21:00</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card bg-dark border-0 text-white">
                    <img src="path/to/mummy_poster.jpg" class="card-img-top rounded-0" alt="Mummy">
                    <div class="card-body p-2">
                        <h5 class="card-title font-weight-bold mb-1">Mummy</h5>
                        <div class="d-flex flex-wrap">
                            <small class="mr-2">15:00</small>
                            <small class="mr-2">17:00</small>
                            <small class="mr-2">19:00</small>
                            <small class="text-danger">21:00</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card bg-dark border-0 text-white">
                    <img src="path/to/mummy_poster.jpg" class="card-img-top rounded-0" alt="Mummy">
                    <div class="card-body p-2">
                        <h5 class="card-title font-weight-bold mb-1">Mummy</h5>
                        <div class="d-flex flex-wrap">
                            <small class="mr-2">15:00</small>
                            <small class="mr-2">17:00</small>
                            <small class="mr-2">19:00</small>
                            <small class="text-danger">21:00</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                </div>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                </div>
            <div class="col-12 mt-4 text-center">
                 <button class="btn btn-danger btn-lg">Show more</button>
            </div>
        </div>
    </div>
</div>







@endsection
