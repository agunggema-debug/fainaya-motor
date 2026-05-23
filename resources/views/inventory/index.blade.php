<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - Warehouse Inventory HUD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0b0f19; }
        .gaming-font { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body class="text-slate-200 min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <!-- TOP NAV HUD -->
    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('services.index') }}" class="text-xs font-bold text-slate-400 hover:text-cyan-400 gaming-font tracking-wider transition-colors">
                    ← MAIN_QUESTBOARD
                </a>
                <span class="text-slate-700">|</span>
                <span class="gaming-font text-sm font-bold text-slate-300">WAREHOUSE_STOCK_HUD</span>
            </div>
            <div class="text-xs text-slate-400 font-mono">
                Operator: <span class="text-cyan-400 font-bold">{{ strtoupper(auth()->user()->role) }}</span>
            </div>
        </div>
    </header>
    <div>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- SYSTEM NOTIFICATION ALERT -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 text-xs text-emerald-400 font-mono shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                ⚡ LOG_UPDATED: {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-500/10 border border-rose-500/30 rounded-xl p-4 text-xs text-rose-400 font-mono shadow-[0_0_15px_rgba(244,63,94,0.1)]">
                ⚠️ REGISTRATION_FAILED:
                <ul class="list-disc list-inside mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- HEADER SECTION -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-950/40 p-6 rounded-2xl border border-slate-800/60">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-white gaming-font uppercase">// LOOT_STOCK_MANAGEMENT</h2>
                <p class="text-xs text-slate-400 mt-1">Gudang suku cadang Fainaya Motor. Item di bawah ambang batas minimal otomatis memicu status krisis.</p>
            </div>
            <div class="flex gap-4">
                <div class="bg-slate-900 border border-slate-800 px-4 py-2 rounded-xl text-center">
                    <span class="block text-[10px] uppercase text-slate-500 font-mono font-bold">Total Varian Suku Cadang</span>
                    <span class="text-lg font-black text-white gaming-font">{{ $items->count() }}</span>
                </div>
                <div class="bg-rose-500/5 border border-rose-500/20 px-4 py-2 rounded-xl text-center">
                    <span class="block text-[10px] uppercase text-rose-400 font-mono font-bold">Butuh Restock Segera</span>
                    <span class="text-lg font-black text-rose-500 gaming-font">
                        {{ $items->filter(fn($item) => $item->isLowStock())->count() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- NEW ITEM REGISTRATION COMPONENT -->
        <div class="mb-8 bg-slate-950/80 border border-purple-900/40 rounded-2xl p-6 shadow-[0_0_30px_rgba(147,51,234,0.05)]">
            <div class="border-b border-slate-800/80 pb-4 mb-4">
                <h3 class="text-sm font-bold text-purple-400 gaming-font uppercase tracking-wider">// LOOT_REGISTRATION_CORE</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftarkan varian suku cadang atau barang baru ke dalam pangkalan data logistik.</p>
            </div>
            
            <form action="{{ route('inventory.index') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                @csrf
                <!-- KODE ITEM -->
                <div class="md:col-span-1">
                    <label class="block text-[10px] uppercase font-mono font-bold text-slate-400 mb-1.5 tracking-wider">Kode Item_</label>
                    <input type="text" name="item_code" value="{{ old('item_code') }}" placeholder="Ex: BRK-001" required
                        class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 font-mono transition-all">
                </div>

                <!-- NAMA BARANG -->
                <div class="md:col-span-1.5">
                    <label class="block text-[10px] uppercase font-mono font-bold text-slate-400 mb-1.5 tracking-wider">Nama Suku Cadang_</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Kampas Rem Depan Vario" required
                        class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 transition-all">
                </div>

                <!-- AMBANG BATAS MINIMAL -->
                <div>
                    <label class="block text-[10px] uppercase font-mono font-bold text-slate-400 mb-1.5 tracking-wider">Min Stok (Limit)_</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" required
                        class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 font-mono transition-all">
                </div>

                <!-- STOK AWAL -->
                <div>
                    <label class="block text-[10px] uppercase font-mono font-bold text-slate-400 mb-1.5 tracking-wider">Stok Awal_</label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" min="0" required
                        class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 font-mono transition-all">
                </div>

                <!-- HARGA JUAL -->
                <div>
                    <label class="block text-[10px] uppercase font-mono font-bold text-slate-400 mb-1.5 tracking-wider">Harga Unit (Rp)_</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="Ex: 45000" min="0" required
                        class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-slate-200 outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 font-mono transition-all">
                </div>

                <!-- TOMBOL EXECUTE -->
                <div class="md:col-span-5 flex justify-end mt-2">
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl transition gaming-font shadow-[0_0_15px_rgba(147,51,234,0.3)]">
                        + REGISTER_NEW_LOOT
                    </button>
                </div>
            </form>
        </div>

        <!-- TABLE INVENTORY LIST -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-900 bg-slate-900/40 text-[10px] font-mono text-slate-400 uppercase tracking-widest">
                            <th class="p-4">Kode Item</th>
                            <th class="p-4">Nama Suku Cadang</th>
                            <th class="p-4 text-center">Ambang Batas (Min)</th>
                            <th class="p-4 text-center">Stok Saat Ini</th>
                            <th class="p-4 text-right">Harga Jual Unit</th>
                            <th class="p-4 text-center">Aksi Gudang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900/60 text-sm">
                        @foreach($items as $item)
                        <!-- Jika stok menembus batas min_stock, beri background merah tipis transparan -->
                        <tr class="transition-colors {{ $item->isLowStock() ? 'bg-rose-500/5 hover:bg-rose-500/10' : 'hover:bg-slate-900/20' }}">
                            
                            <!-- KODE ITEM -->
                            <td class="p-4 font-mono font-bold text-xs {{ $item->isLowStock() ? 'text-rose-400' : 'text-slate-400' }}">
                                {{ $item->item_code }}
                            </td>
                            
                            <!-- NAMA & INDIKATOR KRISIS -->
                            <td class="p-4">
                                <div class="font-semibold text-slate-200 tracking-wide">{{ $item->name }}</div>
                                @if($item->isLowStock())
                                    <span class="inline-flex items-center gap-1 mt-1 text-[9px] font-bold font-mono tracking-widest text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/30 uppercase animate-pulse">
                                        ⚠️ CRITICAL_LOW_STOCK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 mt-1 text-[9px] font-bold font-mono tracking-widest text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20 uppercase">
                                        ✓ STABLE
                                    </span>
                                @endif
                            </td>
                            
                            <!-- MINIMUM STOCK THRESHOLD -->
                            <td class="p-4 text-center font-mono text-slate-500">
                                {{ $item->min_stock }} pcs
                            </td>
                            
                            <!-- LIVE STOCK CURRENT COUNTER -->
                            <td class="p-4 text-center font-mono">
                                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold font-mono {{ $item->isLowStock() ? 'bg-rose-500/20 text-rose-400 border border-rose-500/40' : 'bg-slate-900 text-cyan-400' }}">
                                    {{ $item->stock }} pcs
                                </span>
                            </td>
                            
                            <!-- UNIT PRICE -->
                            <td class="p-4 text-right font-mono text-slate-400">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            
                            <!-- QUICK RESTOCK ACTION ACTION FORM -->
                            <td class="p-4 text-center">
                                <form action="{{ route('inventory.restock', $item->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="number" name="added_stock" value="10" min="1" required
                                        class="w-16 bg-slate-900 border border-slate-800 rounded px-2 py-1 text-xs text-center text-white outline-none focus:border-cyan-500 font-mono">
                                    <button type="submit" 
                                        class="bg-slate-800 hover:bg-cyan-500 hover:text-slate-950 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded border border-cyan-500/30 transition-all font-mono">
                                        + INJECT_STOCK
                                    </button>
                                </form>
                            </td>

                        </tr>
                        @endforeach
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