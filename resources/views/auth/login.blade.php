@extends('layouts.app')

@section('content')
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* Background & Overlay */
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }

        /* Card Glassmorphism Premium */
        .card-glass {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 28px !important;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3) !important;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* Input Group Modern */
        .custom-input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .custom-input-group:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
        }

        .input-field {
            background: transparent !important;
            border: none !important;
            color: white !important;
            padding: 14px 18px !important;
            width: 100%;
            outline: none !important;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        /* Tombol Mata */
        .btn-toggle-eye {
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.5);
            padding: 0 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-toggle-eye:hover {
            color: white;
        }

        /* Tombol Masuk Putih Bersih */
        .btn-masuk {
            background: #ffffff !important;
            color: #1e3a8a !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 14px;
            padding: 14px;
            border: none;
            width: 100%;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .btn-masuk:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: #f8f9fa !important;
        }

        /* Divider */
        .separator {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.7rem;
            margin: 25px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .separator::before,
        .separator::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .separator::before {
            margin-right: 15px;
        }

        .separator::after {
            margin-left: 15px;
        }

        .btn-google {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white !important;
            border-radius: 14px;
            padding: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-google:hover {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5 col-xl-4">

                <div class="text-center mb-5">
                    @if (isset($setting->logo))
                        <img src="{{ asset('storage/' . $setting->logo) }}" width="80" class="mb-3">
                    @endif
                    <h3 class="text-white fw-bold">Selamat Datang</h3>
                    <p class="text-white-50">Silakan masuk ke dashboard Anda</p>
                </div>

                <div class="card glass-card border-0">
                    <div class="card-body p-4 p-lg-5">

                        <a href="#" class="btn btn-google">
                            <img src="{{ asset('assets/images/icons/search.svg') }}" width="18" class="me-2">
                            Sign in with Google
                        </a>

                        <div class="separator">Atau</div>
                        @if (session('error'))
                            <div class="alert alert-danger border-0 bg-danger text-white small mb-4"
                                style="border-radius: 12px; background: rgba(220, 38, 38, 0.8) !important; backdrop-filter: blur(10px);">
                                <div class="d-flex align-items-center">
                                    <i class='bx bx-error-circle me-2' style="font-size: 20px;"></i>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 text-white small mb-4"
                                style="border-radius: 12px; background: rgba(220, 38, 38, 0.8) !important; backdrop-filter: blur(10px);">
                                <ul class="list-unstyled mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class='bx bx-error-circle me-2'></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Alamat Email</label>
                                <div class="custom-input-group">
                                    <input type="email" name="email" class="input-field" placeholder="nama@email.com"
                                        required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Kata Sandi</label>
                                <div class="custom-input-group">
                                    <input type="password" name="password" id="psw_input" class="input-field"
                                        placeholder="••••••••" required>
                                    <button type="button" class="btn-toggle-eye" onclick="togglePasswordView()">
                                        <i class='bx bx-hide' id="eye_icon" style="font-size: 22px;"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                                    <label class="form-check-label text-white-50 small" for="remember">Ingat Saya</label>
                                </div>
                                <a href="#" class="text-white-50 small text-decoration-none">Lupa Password?</a>
                            </div>

                            <button type="submit" class="btn btn-masuk">Masuk Sekarang</button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('home') }}" class="text-white-50 small text-decoration-none">
                                <i class='bx bx-left-arrow-alt'></i> Kembali ke Beranda
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordView() {
            const input = document.getElementById('psw_input');
            const icon = document.getElementById('eye_icon');

            if (input.type === "password") {
                input.type = "text";
                icon.className = 'bx bx-show'; // Ganti class untuk boxicons
            } else {
                input.type = "password";
                icon.className = 'bx bx-hide';
            }
        }
    </script>
@endsection
