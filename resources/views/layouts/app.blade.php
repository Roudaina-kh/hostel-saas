<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hostel SaaS')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Global Animations ── */
        .fade-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 100ms; }
        .delay-2 { animation-delay: 200ms; }
        .delay-3 { animation-delay: 300ms; }

        /* ── Blue Theme Dashboard Cards ── */
        .stat-card {
            background: linear-gradient(135deg, rgba(239, 246, 255, 0.9), rgba(224, 242, 254, 0.8));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(186, 230, 253, 0.5);
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(186, 230, 253, 0.2);
            transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-20deg);
            transition: 0.5s;
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(186, 230, 253, 0.4);
            border-color: rgba(125, 211, 252, 0.8);
        }
        .stat-card:hover::before { left: 150%; }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            background: #FFFFFF;
            box-shadow: 0 4px 12px rgba(186, 230, 253, 0.6);
            border: 1px solid #E0F2FE;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .stat-card:hover .stat-icon { transform: scale(1.15) rotate(5deg); }

        /* ── Buttons ── */
        .btn-blue {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 15px;
            padding: 0.875rem 1.75rem;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .btn-blue:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.35);
        }
        .btn-blue svg { transition: transform 0.3s ease; }
        .btn-blue:hover svg { transform: rotate(90deg) scale(1.1); }

        /* ── Table Container & Headers ── */
        .glass-table {
            background: #FFFFFF;
            border: 1px solid #E8EEF2;
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }
        .table-header-blue {
            background: linear-gradient(90deg, #F0F9FF 0%, #E0F2FE 100%);
            border-bottom: 2px solid #BAE6FD;
        }
        .table-row-hover {
            transition: background 0.2s, transform 0.2s;
        }
        .table-row-hover:hover {
            background: #F8FAFC;
        }
        td, th { padding: 1.25rem 1.75rem !important; }

    </style>
</head>
<body class="antialiased min-h-screen flex selection:bg-[#3B82F6] selection:text-white"
      style="background-color: #F8FAFC; color: #1E293B; font-family: 'Inter', sans-serif;">

    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col min-h-screen relative overflow-hidden">
        {{-- Ambient Blobs for App Layout --}}
        <div class="absolute w-[600px] h-[600px] bg-white/60 rounded-full blur-[100px] -top-[10%] -left-[10%] pointer-events-none z-0"></div>
        <div class="absolute w-[500px] h-[500px] bg-[#EAD8C8]/40 rounded-full blur-[90px] bottom-[10%] -right-[5%] pointer-events-none z-0"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            @include('layouts.header')
            
            <main class="p-8 sm:p-10 flex-1 w-full max-w-7xl mx-auto">

                @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: '{{ session('success') }}',
                        timer: 2500,
                        showConfirmButton: false,
                        background: '#FDFAF5',
                        color: '#1A2B3C',
                    });
                });
            </script>
            @endif

            @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: '{{ session('error') }}',
                        background: '#FDFAF5',
                        color: '#1A2B3C',
                    });
                });
            </script>
            @endif

            @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
