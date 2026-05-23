<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - Cashier Station</title>
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f19;
        }
        .gaming-font {
            font-family: 'Orbitron', sans-serif;
        }
        .neon-border-cyan {
            box-shadow: 0 0 12px rgba(6, 182, 212, 0.25), inset 0 0 10px rgba(6, 182, 212, 0.1);
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black flex flex-col justify-between">

    <div>
        <!-- TOP NAVIGATION HUD -->
        <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('services.index') }}" class="gaming-font text-xl font-black tracking-wider bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent hover:opacity-80 transition-opacity">
                        &lt; FAINAYA_MOTOR
                    </a>
                    <span class="bg-cyan-950 text-[10px] text-cyan-400 font-bold px-2 py-0.5 rounded uppercase tracking-widest gaming-font border border-cyan-500/30">
                        CASHIER_STATION
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-500 font-mono">STATION_OP</p>
                    <p class="font-semibold text-slate-300 gaming-font text-xs tracking-wide">{{ Auth::user()->name ?? 'CASHIER_AGENT' }}</p>
                </div>
            </div>
        </header>

        <!-- MAIN FORM WORKSPACE -->
        <main class="max-w-2xl mx-auto px-4 py-12">
            
            <!-- SECTION HEADER -->
            <div class="mb-8 flex items-center justify-between border-b border-slate-800 pb-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-white gaming-font uppercase">// REGISTER_NEW_QUEST</h2>
                    <p class="text-xs text-slate-400 mt-1">Input cepat data unit kendaraan untuk antrean mekanik</p>
                </div>
                <div class="text-right bg-slate-900/40 p-3 rounded-xl border border-slate-800">
                    <p class="text-[9px] text-slate-500 font-mono tracking-widest uppercase">Issued Today</p>
                    <p class="text-xl font-black text-cyan-400 gaming-font">{{ $todayQueueCount }} <span class="text-[10px] text-slate-400 font-normal">UNITS</span></p>
                </div>
            </div>

            <!-- SYSTEM ERROR DISPLAY -->
            @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl p-4 text-xs font-mono text-red-400">
                <p class="font-bold uppercase tracking-wider mb-1">!! REGISTRATION_FAILED_INTERRUPT</p>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- FAST INPUT COMPONENT FORM -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 shadow-2xl neon-border-cyan backdrop-blur-md">
                <form action="{{ route('services.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="space-y-5">
                        
                        <!-- 1. INPUT PLAT NOMOR (CRITICAL LOG) -->
                        <div>
                            <label for="license_plate" class="block text-xs font-bold text-cyan-400 tracking-wider uppercase gaming-font mb-2">
                                [01] Plat Nomor Kendaraan *
                            </label>
                            <input 
                                type="text" 
                                name="license_plate" 
                                id="license_plate" 
                                required 
                                placeholder="E.g., D1234ABC"
                                value="{{ old('license_plate') }}"
                                class="w-full bg-slate-900/80 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-white rounded-xl px-4 py-3 text-sm font-mono tracking-widest uppercase transition-all placeholder:text-slate-600 focus:outline-none"
                            >
                            <p class="text-[10px] text-slate-500 font-mono mt-1.5">// Sistem akan mencocokkan riwayat entitas secara otomatis.</p>
                        </div>

                        <!-- 2. INPUT NAMA PELANGGAN -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 tracking-wider uppercase gaming-font mb-2">
                                [02] Nama Pemilik / Driver *
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                required 
                                placeholder="Masukkan nama lengkap pelanggan"
                                value="{{ old('name') }}"
                                class="w-full bg-slate-900/80 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-white rounded-xl px-4 py-3 text-sm transition-all placeholder:text-slate-600 focus:outline-none"
                            >
                        </div>

                        <!-- 2 KOLOM KOORDINAT: TIPE MOTOR & TELEPON -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- TIPE MOTOR -->
                            <div>
                                <label for="motorcycle_type" class="block text-xs font-bold text-slate-400 tracking-wider uppercase gaming-font mb-2">
                                    [03] Tipe Kendaraan *
                                </label>
                                <input 
                                    type="text" 
                                    name="motorcycle_type" 
                                    id="motorcycle_type" 
                                    required 
                                    placeholder="E.g., VARIO 150, NMAX"
                                    value="{{ old('motorcycle_type') }}"
                                    class="w-full bg-slate-900/80 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-white rounded-xl px-4 py-3 text-sm uppercase transition-all placeholder:text-slate-600 focus:outline-none"
                                >
                            </div>

                            <!-- NOMOR TELEPON (OPSIONAL) -->
                            <div>
                                <label for="phone" class="block text-xs font-bold text-slate-400 tracking-wider uppercase gaming-font mb-2">
                                    [04] No. Telepon Contact
                                </label>
                                <input 
                                    type="text" 
                                    name="phone" 
                                    id="phone" 
                                    placeholder="E.g., 081234XXXX"
                                    value="{{ old('phone') }}"
                                    class="w-full bg-slate-900/80 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-white rounded-xl px-4 py-3 text-sm font-mono transition-all placeholder:text-slate-600 focus:outline-none"
                                >
                            </div>
                        </div>

                    </div>

                    <!-- SUBMIT SYSTEM COMMAND -->
                    <div class="mt-8 pt-4 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('services.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors font-mono uppercase tracking-wide">
                            [ Esc ] Kembali ke Dashboard
                        </a>
                        
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-slate-950 font-bold uppercase tracking-widest px-6 py-3 rounded-xl transition gaming-font text-xs shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)] flex items-center justify-center space-x-2"
                        >
                            <span>GENERATE_QUEUE_NUMBER_</span>
                        </button>
                    </div>

                </form>
            </div>

        </main>
    </div>

    <!-- HUD FOOTER -->
    <footer class="border-t border-slate-900 bg-slate-950/40 backdrop-blur py-4">
        <div class="max-w-2xl mx-auto px-4 flex justify-between text-[10px] font-mono text-slate-600 tracking-wide">
            <span>FAINAYA MOTOR ERP // CAS-SYS-ID</span>
            <span class="text-cyan-500/40">READY_TO_AUTO_GENERATE_TICKET</span>
        </div>
    </footer>

</body>
</html>