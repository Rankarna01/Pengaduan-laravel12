<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ $globalSettings['system_name'] ?? 'Pengaduan Desa' }}</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Pelaporan Kerusakan Infrastruktur Desa — Laporkan dan pantau kerusakan infrastruktur di lingkungan Anda.')">

    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary:   { DEFAULT: '#2563eb', dark: '#1d4ed8', light: '#3b82f6', 50: '#eff6ff', 100: '#dbeafe' },
                        surface:   '#f8fafc',
                        secondary: '#64748b',
                        success:   { DEFAULT: '#22c55e', dark: '#16a34a', light: '#4ade80', 50: '#f0fdf4' },
                        danger:    { DEFAULT: '#ef4444', dark: '#dc2626', 50: '#fef2f2' },
                        warning:   { DEFAULT: '#f59e0b', dark: '#d97706', 50: '#fffbeb' },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out both',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%':   { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    {{-- Font Awesome 6 Free --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- AOS Animate on Scroll --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Custom global styles --}}
    <style>
        * { font-family: 'Poppins', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 3px; }
        .gradient-primary { background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%); }
        .gradient-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); }
        .glass-dark { background: rgba(0,0,0,0.2); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); }
        .card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(37,99,235,0.15); }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(37,99,235,0.12); color: #2563eb; }
        .sidebar-link.active { border-left: 3px solid #2563eb; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); transition: all 0.3s ease; }
        .btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #1e40af); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }
        .status-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.025em; }
        .animate-number { transition: all 1s ease; }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
    </style>

    @stack('styles')
</head>
<body class="bg-surface text-gray-800 antialiased">

    @yield('content')

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    {{-- AOS --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Init AOS
        AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic' });

        // Global CSRF token for AJAX
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Global AJAX helper
        async function ajaxRequest(url, method = 'GET', data = null, isFormData = false) {
            const options = {
                method,
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            };
            if (data) {
                if (isFormData) {
                    options.body = data;
                } else {
                    options.headers['Content-Type'] = 'application/json';
                    options.body = JSON.stringify(data);
                }
            }
            const res = await fetch(url, options);
            return res.json();
        }

        // Global SweetAlert flash handler
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session("success") }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Error!', text: '{{ session("error") }}', timer: 3000, showConfirmButton: false, toast: true, position: 'top-end' });
        @endif

        // Global delete confirm helper
        function confirmDelete(url, callback) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const res = await ajaxRequest(url, 'DELETE');
                    if (res.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Dihapus!', text: res.message, timer: 1800, showConfirmButton: false, toast: true, position: 'top-end' });
                        if (callback) callback();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: res.message });
                    }
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
