<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - Quest Detail #{{ $service->queue_number }}</title>
    <!-- Google Fonts & Tailwind CDN -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0b0f19; }
        .gaming-font { font-family: 'Orbitron', sans-serif; }
        .neon-border-amber { box-shadow: 0 0 15px rgba(245, 158, 11, 0.15); }
    </style>
</head>
<body class="text-slate-200 min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <!-- TOP NAV HUD -->
    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('services.index') }}" class="text-xs font-bold text-slate-400 hover:text-cyan-400 gaming-font tracking-wider transition-colors">
                    ← BACK_TO_BOARD
                </a>
                <span class="text-slate-700">|</span>
                <span class="gaming-font text-sm font-bold text-slate-300">QUEST_MANAGE_HUD</span>
            </div>
            <div class="gaming-font text-xs text-amber-500 font-bold px-3 py-1 rounded border border-amber-500/20 bg-amber-500/5 uppercase tracking-widest">
                STATUS: {{ strtoupper($service->status) }}
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- ALERT SYSTEM OVERRIDE -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 text-xs text-emerald-400 font-mono">
                🚀 SYSTEM_SUCCESS: {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-500/10 border border-rose-500/30 rounded-xl p-4 text-xs text-rose-400 font-mono">
                ⚠️ SYSTEM_ALERT: {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT PANEL: VEHICLE & OWNER STATS -->
            <div class="space-y-6">
                <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 neon-border-amber">
                    <h3 class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font mb-4">// VEHICLE_SPECS</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] text-slate-500 font-mono uppercase">Plat Nomor</label>
                            <p class="text-2xl font-black text-white tracking-wider gaming-font">{{ $service->customer->license_plate }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-t border-slate-900 pt-3">
                            <div>
                                <label class="text-[10px] text-slate-500 font-mono uppercase">Tipe Motor</label>
                                <p class="text-sm font-bold text-slate-300">{{ $service->customer->motorcycle_type }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 font-mono uppercase">Rider (Nama)</label>
                                <p class="text-sm font-bold text-slate-300">{{ $service->customer->name }}</p>
                            </div>
                        </div>
                        <div class="border-t border-slate-900 pt-3">
                            <label class="text-[10px] text-slate-500 font-mono uppercase">No. Antrean</label>
                            <p class="text-sm font-mono text-cyan-400 font-bold">{{ $service->queue_number }}</p>
                        </div>
                    </div>
                </div>

                <!-- 🏁 👇 DI SINI: MODUL INPUT DATA STNK FISIK KENDARAAN -->
                @if($service->status === 'processing')
                    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 relative overflow-hidden">
                        <!-- Garis Aksen Oranye sebagai indikator pengerjaan fisik -->
                        <div class="absolute top-0 left-0 w-full h-1 bg-amber-500"></div>
                        
                        <h3 class="text-xs font-bold text-amber-500 tracking-wider uppercase gaming-font mb-4">// STNK_VERIFICATION</h3>

                        <!-- JIKA DATA STNK BELUM PERNAH DIISI -->
                        @if(!$service->customer->frame_number)
                            <p class="text-[11px] text-slate-400 mb-4 leading-relaxed">Unit sedang dibongkar. Silakan log data identitas fisik dari STNK pelanggan ke sistem database.</p>
                            
                            <form action="{{ route('services.update-stnk', $service->id) }}" method="POST" autocomplete="off" class="space-y-4">
                                @csrf
                                
                                <!-- INPUT NOMOR RANGKA -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1">No. Rangka (VIN) *</label>
                                    <input type="text" name="frame_number" required placeholder="Contoh: MHIJBXXXXXXXX..." 
                                           class="w-full bg-slate-900 border border-slate-800 focus:border-amber-500 text-white font-mono uppercase rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-amber-500">
                                </div>

                                <!-- INPUT NOMOR MESIN -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1">No. Mesin *</label>
                                    <input type="text" name="engine_number" required placeholder="Contoh: JB51EXXXXXXXX..." 
                                           class="w-full bg-slate-900 border border-slate-800 focus:border-amber-500 text-white font-mono uppercase rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-amber-500">
                                </div>

                                <!-- INPUT KAPASITAS MESIN (CC) -->
                                <div>
                                    <label class="block text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1">Kapasitas Mesin (CC) *</label>
                                    <div class="relative">
                                        <input type="number" name="engine_capacity" required placeholder="150" 
                                               class="w-full bg-slate-900 border border-slate-800 focus:border-amber-500 text-white font-mono rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-amber-500 pr-8">
                                        <span class="absolute right-3 top-2 text-[10px] font-mono text-slate-500">cc</span>
                                    </div>
                                </div>

                                <!-- TOMBOL SUBMIT LOG STNK -->
                                <div class="pt-2">
                                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-amber-400 border border-amber-500/30 text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg transition-all font-mono shadow-[0_0_10px_rgba(245,158,11,0.1)]">
                                        SAVE_STNK_DATA_
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- JIKA DATA STNK SUDAH ADA (VERIFIED) -->
                            <div class="space-y-3 font-mono text-xs">
                                <div class="bg-emerald-500/5 border border-emerald-500/20 p-2 rounded-lg text-center text-emerald-400 text-[10px] font-bold tracking-widest uppercase mb-2">
                                    ✓ IDENTITY_VERIFIED_SECURE
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-500 uppercase block">No. Rangka</span>
                                    <span class="text-sm font-semibold text-slate-200 tracking-wider">{{ $service->customer->frame_number }}</span>
                                </div>
                                <div class="border-t border-slate-900 pt-2">
                                    <span class="text-[10px] text-slate-500 uppercase block">No. Mesin</span>
                                    <span class="text-sm font-semibold text-slate-200 tracking-wider">{{ $service->customer->engine_number }}</span>
                                </div>
                                <div class="border-t border-slate-900 pt-2">
                                    <span class="text-[10px] text-slate-500 uppercase block">Isi Silinder</span>
                                    <span class="text-sm font-bold text-amber-400">{{ $service->customer->engine_capacity }} cc</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <!-- 🏁 👆 DATA STNK END -->

                <!-- LIVE TOTAL PRICE HUD -->
                <div class="bg-gradient-to-br from-slate-950 to-slate-900 border border-slate-800 rounded-2xl p-6">
                    <h3 class="text-xs font-bold text-slate-500 tracking-wider uppercase gaming-font mb-2">// TOTAL_REPAIR_COST</h3>
                    <p class="text-3xl font-black text-purple-400 gaming-font tracking-tight">
                        Rp {{ number_format($service->total_price, 0, ',', '.') }}
                    </p>
                    
                    <!-- TOMBOL SELESAI QUEST (Hanya muncul jika status masih processing) -->
                    @if($service->status === 'processing')
                        @can('access-mechanic')
                        <form action="{{ route('services.complete', $service->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-slate-950 text-xs font-bold uppercase tracking-wider py-3 rounded-xl transition gaming-font shadow-[0_0_15px_rgba(245,158,11,0.3)]">
                                ✓ Complete Repair Quest
                            </button>
                        </form>
                        @endcan
                    @endif
                </div>
            </div>

            <!-- RIGHT PANEL: ACTION INPUT & SERVICE DETAIL LIST -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- INPUT LOOT & ACTION (Hanya bisa diisi jika status pengerjaan aktif) -->
                @if($service->status === 'processing')
                    @can('access-mechanic')
                    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white tracking-wide uppercase gaming-font mb-4">// ADD_ACTION_OR_SPAREPART</h3>
                        
                        <!-- Form hit control Controller @addAction -->
                        <form action="{{ route('services.add-action', $service->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1.5 font-mono">Nama Tindakan / Jasa</label>
                                    <input type="text" name="action_name" required placeholder="Contoh: Ganti Oli Mesin / Pasang Ban"
                                        class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-amber-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1.5 font-mono">Link Loot Item (Suku Cadang)</label>
                                    <!-- Ambil data $items dari backend untuk pilihan sparepart -->
                                    <select name="item_id" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-400 outline-none focus:border-amber-500 transition-colors">
                                        <option value="">-- Jasa Murni (Tanpa Ganti Sparepart) --</option>
                                        @foreach(\App\Models\Item::where('stock', '>', 0)->get() as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->item_code }} - {{ $item->name }} (Stok: {{ $item->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1.5 font-mono">Quantity</label>
                                    <input type="number" name="quantity" value="1" min="1" required
                                        class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-amber-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1.5 font-mono">Biaya / Harga Jual (Rp)</label>
                                    <input type="number" name="price" required placeholder="50000"
                                        class="w-full bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 outline-none focus:border-amber-500 transition-colors">
                                </div>
                                <div class="col-span-2 md:col-span-1 flex items-end">
                                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-amber-400 border border-amber-500/30 text-xs font-bold uppercase tracking-wider py-2.5 rounded-lg transition-all font-mono">
                                        + Inject Action
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endcan
                @endif

                <!-- SUMMARY LOG ACTIONS INSTALLED -->
                <div class="bg-slate-950/80 border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="p-4 bg-slate-900/30 border-b border-slate-800">
                        <h3 class="text-xs font-bold text-slate-400 tracking-wider uppercase gaming-font">// INSTALLED_LOOT_LOG</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-900 bg-slate-900/10 text-[10px] font-mono text-slate-500 uppercase tracking-widest">
                                    <th class="p-4">Deskripsi Aktivitas</th>
                                    <th class="p-4 text-center">Qty</th>
                                    <th class="p-4 text-right">Unit Price</th>
                                    <th class="p-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-900/60 text-sm">
                                @forelse($service->details as $detail)
                                <tr class="hover:bg-slate-900/20 transition-colors">
                                    <td class="p-4">
                                        <div class="font-semibold text-slate-200 tracking-wide">{{ $detail->action_name }}</div>
                                        @if($detail->item)
                                            <div class="text-[11px] text-slate-500 font-mono mt-0.5">Code Binded: {{ $detail->item->item_code }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center font-mono text-slate-400">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="p-4 text-right font-mono text-slate-400">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 text-right font-mono text-emerald-400 font-semibold">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-slate-600 font-mono text-xs italic">
                                        // No action logs injected into this unit yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
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