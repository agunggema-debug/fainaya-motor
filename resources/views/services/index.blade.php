<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - HUD Dashboard</title>
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
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.2), inset 0 0 10px rgba(6, 182, 212, 0.1);
        }
        .neon-border-amber {
            box-shadow: 0 0 10px rgba(245, 158, 11, 0.15), inset 0 0 10px rgba(245, 158, 11, 0.05);
        }
        .neon-border-purple {
            box-shadow: 0 0 10px rgba(168, 85, 247, 0.15), inset 0 0 10px rgba(168, 85, 247, 0.05);
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black flex flex-col justify-between">

    <div>
        <!-- TOP NAVIGATION HUD -->
        <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="gaming-font text-2xl font-black tracking-wider bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        FAINAYA_MOTOR
                    </span>
                    <span class="bg-slate-800 text-[10px] text-cyan-400 font-bold px-2 py-0.5 rounded uppercase tracking-widest gaming-font border border-cyan-500/30">
                        ERP v1.0
                    </span>
                </div>
                
                <div class="flex items-center space-x-6 text-sm">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-slate-500">CURRENT_USER</p>
                        <p class="font-semibold text-slate-300 gaming-font text-xs tracking-wide">{{ Auth::user()->name ?? 'GUILD_MASTER' }}</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-cyan-500 to-purple-600 flex items-center justify-center font-bold text-xs shadow-[0_0_10px_rgba(6,182,212,0.4)]">
                        {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                    </div>
                    
                    <!-- LOGOUT BUTTON SYSTEM -->
                    <div class="border-l border-slate-800 pl-4">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="bg-red-500/10 hover:bg-red-500 hover:text-slate-950 text-red-400 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded border border-red-500/20 transition-all gaming-font shadow-[0_0_10px_rgba(239,68,68,0.1)]">
                                LOGOUT_
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- STATUS TILES (STATISTICS BAR) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5 backdrop-blur-sm neon-border-cyan">
                    <p class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font">Queue Antrean</p>
                    <p class="text-3xl font-black text-cyan-400 gaming-font mt-1">
                        {{ $services->where('status', 'queue')->count() }} <span class="text-xs text-slate-500 font-normal">MOTORS</span>
                    </p>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5 backdrop-blur-sm shadow-[0_0_10px_rgba(245,158,11,0.05)]">
                    <p class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font">Active Quest</p>
                    <p class="text-3xl font-black text-amber-500 gaming-font mt-1">
                        {{ $services->where('status', 'processing')->count() }} <span class="text-xs text-slate-500 font-normal">REPAIRS</span>
                    </p>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font">Quest Cleared</p>
                    <p class="text-3xl font-black text-emerald-400 gaming-font mt-1">
                        {{ $services->where('status', 'done')->count() }} <span class="text-xs text-slate-500 font-normal">UNPAID</span>
                    </p>
                </div>
                <div class="bg-slate-900/60 border border-slate-800 rounded-xl p-5 backdrop-blur-sm">
                    <p class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font">Gold Collected</p>
                    <p class="text-xl font-black text-purple-400 gaming-font mt-2">
                        Rp {{ number_format($services->where('status', 'paid')->sum('total_price'), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- ROLE-BASED QUICK ACCESS MENU GRID -->
            <div class="mb-8">
                <h3 class="text-xs font-bold text-slate-500 tracking-widest uppercase gaming-font mb-3 block">// SYSTEM_MODULES_ACCESS</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    
                    <!-- CASHIER MENU -->
                    @can('access-cashier')
                    <div class="bg-slate-950/60 border border-slate-800 hover:border-cyan-500/50 rounded-xl p-4 transition-all group backdrop-blur-sm neon-border-cyan">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-cyan-400 font-bold tracking-widest uppercase gaming-font bg-cyan-950/50 px-2 py-0.5 rounded border border-cyan-500/20">CASHIER_STATION</span>
                            <span class="h-1.5 w-1.5 rounded-full bg-cyan-400 block animate-ping"></span>
                        </div>
                        <h4 class="font-bold text-white tracking-wide gaming-font group-hover:text-cyan-400 transition-colors">Point of Sales &amp; Invoice</h4>
                        <p class="text-xs text-slate-400 mt-1">Manajemen pembayaran, input antrean unit baru, dan cetak struk klaim transaksi.</p>
                        <div class="mt-4 flex space-x-2">
                            <!-- Ubah tombol pada Dashboard Utama Anda menjadi seperti ini -->
                        <a href="{{ route('services.create') }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-slate-950 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition gaming-font shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                            + Register New Unit
                        </a>
                        </div>
                    </div>
                    @endcan

                    <!-- MECHANIC MENU -->
                    @can('access-mechanic')
                    <div class="bg-slate-950/60 border border-slate-800 hover:border-amber-500/50 rounded-xl p-4 transition-all group backdrop-blur-sm neon-border-amber">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-amber-400 font-bold tracking-widest uppercase gaming-font bg-amber-950/50 px-2 py-0.5 rounded border border-amber-500/20">WORKSHOP_BAY</span>
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400 block"></span>
                        </div>
                        <h4 class="font-bold text-white tracking-wide gaming-font group-hover:text-amber-400 transition-colors">Mechanic Worklogs</h4>
                        <p class="text-xs text-slate-400 mt-1">Progres perbaikan, diagnosis kerusakan sistem mekanis, serta alokasi penggunaan loot / sparepart.</p>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('services.index') }}" class="bg-gradient-to-r from-amber-500 to-indigo-600 hover:from-amber-400 hover:to-blue-500 text-slate-950 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition gaming-font shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                                SERVICES &gt;</a>
                        </div>
                    </div>
                    @endcan

                    <!-- WAREHOUSE MENU -->
                    @can('access-warehouse')
                    <div class="bg-slate-950/60 border border-slate-800 hover:border-purple-500/50 rounded-xl p-4 transition-all group backdrop-blur-sm neon-border-purple">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] text-purple-400 font-bold tracking-widest uppercase gaming-font bg-purple-950/50 px-2 py-0.5 rounded border border-purple-500/20">INVENTORY_VAULT</span>
                            <span class="h-1.5 w-1.5 rounded-full bg-purple-400 block"></span>
                        </div>
                        <h4 class="font-bold text-white tracking-wide gaming-font group-hover:text-purple-400 transition-colors">Stock Control &amp; Items</h4>
                        <p class="text-xs text-slate-400 mt-1">Pantau kapasitas gudang sparepart, adjustment stok masuk/keluar, dan manajemen vendor.</p>
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('inventory.index') }}" class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-400 hover:to-indigo-500 text-slate-950 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition gaming-font shadow-[0_0_15px_rgba(147,51,234,0.4)]">
                                INVENTORY &gt;</a>
                        </div>
                    </div>
                    @endcan

                </div>
            </div>

            <!-- MAIN WORKSPACE: ACTIVE QUESTBOARD -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl mb-4">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between bg-slate-900/40">
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-white gaming-font uppercase">Active Questboard</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Pemantauan real-time antrean dan pengerjaan mekanik</p>
                    </div>
                    @can('access-cashier')
                    <form action="{{ route('services.index') }}" method="GET" class="flex items-center space-x-2">
                        <div class="relative">
                            <!-- Ikon Kaca Pembesar -->
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <!-- Input Box -->
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Plat / Nama / Unit..." 
                                class="bg-slate-900 border border-slate-800 text-slate-200 text-xs rounded-lg pl-9 pr-4 py-2.5 w-56 focus:outline-none focus:border-cyan-500 font-mono transition-colors focus:ring-1 focus:ring-cyan-500/30">
                        </div>
                        <!-- Tombol Submit Cari -->
                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-slate-950 text-xs font-bold uppercase tracking-wider px-4 py-2.5 rounded-lg transition gaming-font shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                            SEARCH
                        </button>
                        
                        <!-- Tombol Reset (Hanya muncul jika sedang dalam mode pencarian) -->
                        @if(request('search'))
                            <a href="{{ route('services.index') }}" class="border border-slate-800 hover:border-slate-700 text-slate-400 hover:text-slate-200 p-2.5 rounded-lg text-xs font-mono transition-colors" title="Clear Search">
                                ✕
                            </a>
                        @endif
                    </form>
                    @endcan
                </div>

                <!-- TABLE MONITOR HUD -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-900/20 text-xs font-bold text-slate-400 tracking-wider uppercase gaming-font">
                                <th class="p-4">No. HUD</th>
                                <th class="p-4">Unit Kendaraan</th>
                                <th class="p-4">Mekanik Assigned</th>
                                <th class="p-4">Status Progres</th>
                                <th class="p-4 text-right">Loot Cost</th>
                                <th class="p-4 text-center">Aksi Sistem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-900 text-sm">
                            @forelse($services as $service)
                            <tr class="hover:bg-slate-900/40 transition-colors">
                                <!-- No Antrean -->
                                <td class="p-4 font-mono font-bold text-cyan-400 gaming-font text-xs">
                                    {{ $service->queue_number }}
                                </td>
                                <!-- Kendaraan & Pelanggan -->
                                <td class="p-4">
                                    <div class="font-semibold text-white tracking-wide uppercase">{{ $service->customer->license_plate }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $service->customer->motorcycle_type }} • {{ $service->customer->name }}</div>
                                </td>
                                <!-- Mekanik -->
                                <td class="p-4">
                                    @if($service->mechanic)
                                        <span class="inline-flex items-center space-x-1.5 text-slate-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-purple-400 block shadow-[0_0_6px_#c084fc]"></span>
                                            <span class="text-xs font-mono uppercase tracking-wide">{{ $service->mechanic->name }}</span>
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-500 italic">No Unit Assigned</span>
                                    @endif
                                </td>
                                <!-- Status -->
                                <td class="p-4">
                                    @if($service->status == 'queue')
                                        <span class="bg-cyan-500/10 text-cyan-400 text-[10px] font-bold px-2 py-1 rounded border border-cyan-500/30 uppercase tracking-widest gaming-font">In Queue</span>
                                    @elseif($service->status == 'processing')
                                        <span class="bg-amber-500/10 text-amber-400 text-[10px] font-bold px-2 py-1 rounded border border-amber-500/30 uppercase tracking-widest gaming-font animate-pulse">Grinding</span>
                                    @elseif($service->status == 'done')
                                        <span class="bg-emerald-500/10 text-emerald-400 text-[10px] font-bold px-2 py-1 rounded border border-emerald-500/30 uppercase tracking-widest gaming-font">Quest Clear</span>
                                    @else
                                        <span class="bg-purple-500/10 text-purple-400 text-[10px] font-bold px-2 py-1 rounded border border-purple-500/30 uppercase tracking-widest gaming-font shadow-[0_0_8px_rgba(168,85,247,0.2)]">Paid</span>
                                    @endif
                                </td>
                                <!-- Total Biaya -->
                                <td class="p-4 text-right font-mono text-slate-300 font-semibold text-xs">
                                    Rp {{ number_format($service->total_price, 0, ',', '.') }}
                                </td>
                                <!-- Tombol Trigger State Transaksi -->
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($service->status == 'queue')
                                            @can('access-mechanic')
                                            <form action="{{ route('services.start', $service->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-slate-800 hover:bg-cyan-500 hover:text-slate-950 text-cyan-400 text-[11px] font-bold tracking-wide uppercase px-3 py-1.5 rounded border border-cyan-500/20 transition-all">
                                                    Accept Quest
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-xs text-slate-500 italic font-mono">Waiting for Mech</span>
                                            @endcan

                                        @elseif($service->status == 'processing')
                                            @can('access-mechanic')
                                            <a href="{{ route('services.show', $service->id) }}" class="bg-amber-500/20 hover:bg-amber-500 hover:text-slate-950 text-amber-400 text-[11px] font-bold tracking-wide uppercase px-3 py-1.5 rounded border border-amber-500/30 transition-all">
                                                Manage Loot
                                            </a>
                                            @else
                                                <span class="text-xs text-amber-500/70 italic font-mono">Under Repair</span>
                                            @endcan
                                        
                                        @elseif($service->status == 'done')
                                            @can('access-cashier')
                                            <form action="{{ route('services.pay', $service->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-[11px] font-bold tracking-wide uppercase px-3 py-1.5 rounded transition-all shadow-[0_0_10px_rgba(16,185,129,0.3)]">
                                                    Collect Gold
                                                </button>
                                            </form>
                                            @else
                                                <span class="text-xs text-emerald-500/70 italic font-mono">Waiting Payment</span>
                                            @endcan
                                        
                                        @else
                                            <!-- KONDISI LUNAS (PAID): Akses Cetak Invoice & View Log -->
                                            <div class="flex items-center space-x-2">
                                                @can('access-cashier')
                                                <a href="{{ route('services.invoice', $service->id) }}" target="_blank"
                                                class="bg-slate-900 hover:bg-purple-500 hover:text-slate-950 text-purple-400 text-[11px] font-bold tracking-wide uppercase px-3 py-1.5 rounded border border-purple-500/30 transition-all shadow-[0_0_10px_rgba(168,85,247,0.1)]">
                                                    🖨️ Print Invoice
                                                </a>
                                                @endcan
                                                
                                                <a href="{{ route('services.show', $service->id) }}" class="text-xs text-slate-500 hover:text-slate-300 underline font-mono">
                                                    View Log
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 italic font-mono text-xs">
                                    // No Active Quests Found on the Board.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- TERMINAL FOOTER HUD -->
    <footer class="border-t border-slate-900 bg-slate-950/40 backdrop-blur py-4 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-[11px] font-mono text-slate-500 tracking-wide">
            <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                <span>&copy; {{ date('Y') }} FAINAYA_MOTOR. ALL_RIGHTS_RESERVED.</span>
                <span class="hidden md:inline text-slate-700">|</span>
                <span class="hidden md:inline text-cyan-500/60 animate-pulse">STATUS: OPERATIONAL_SYSTEM_OK</span>
            </div>
            <div class="flex space-x-6">
                <span class="hover:text-slate-400 cursor-pointer">CORE_SECURE_LAYER</span>
                <span class="hover:text-slate-400 cursor-pointer">STATION_ID: {{ Request::ip() }}</span>
            </div>
        </div>
    </footer>

</body>
</html>