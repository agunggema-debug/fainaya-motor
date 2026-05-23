<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - Invoice #{{ $service->queue_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0b0f19; }
        .gaming-font { font-family: 'Orbitron', sans-serif; }
        
        /* CSS KHUSUS SAAT DICETAK KE KERTAS */
        @media print {
            body { background: white; color: black; }
            .no-print { display: none !important; }
            .print-card { border: 1px solid #000 !important; background: transparent !important; color: black !important; box-shadow: none !important; }
            .text-white, .text-slate-200, .text-slate-300 { color: black !important; }
            .text-cyan-400, .text-purple-400 { color: #000 !important; font-weight: bold !important; }
            .bg-slate-950\/80, .bg-slate-900\/40, .bg-slate-900 { background: transparent !important; border-color: #ccc !important; }
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen p-4 sm:p-8 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <div class="max-w-4xl mx-auto">
        
        <!-- ACTION BUTTONS (NO PRINT) -->
        <div class="mb-6 flex justify-between items-center no-print">
            <a href="{{ route('services.index') }}" class="text-xs font-bold text-slate-400 hover:text-cyan-400 gaming-font transition-colors">
                ← BACK_TO_DASHBOARD
            </a>
            <button onclick="window.print()" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-xl transition gaming-font shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                ⚙️ PRINT_RECEIPT_HUD
            </button>
        </div>

        <!-- NOTIFICATION SYSTEM -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4 text-xs text-emerald-400 font-mono no-print">
                ⚡ {{ session('success') }}
            </div>
        @endif

        <!-- MAIN INVOICE CARD -->
        <div class="print-card bg-slate-950/80 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl mb-6">
            
            <!-- INVOICE HEADER -->
            <div class="flex flex-col sm:flex-row justify-between items-start border-b border-slate-800 pb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-black text-white gaming-font tracking-wide">FAINAYA MOTOR</h1>
                    <p class="text-xs text-slate-400 mt-1 font-mono">Baleendah - Ciparay - Majalaya, Kab. Bandung</p>
                    <p class="text-[11px] text-slate-500 font-mono">Sistem Integrasi Mekanik & Suku Cadang Otomatis</p>
                </div>
                <div class="text-left sm:text-right font-mono text-xs">
                    <div class="text-cyan-400 font-bold text-sm gaming-font">{{ $service->queue_number }}</div>
                    <div class="text-slate-400 mt-1">STATUS: <span class="text-emerald-400 font-bold uppercase">{{ $service->status }}</span></div>
                    <div class="text-slate-500 text-[11px] mt-0.5">Waktu Lunas: {{ $service->paid_at ?? $service->updated_at }}</div>
                </div>
            </div>

            <!-- VEHICLE MEDICAL RECORD (REKAM MEDIS IDENTITAS KENDARAAN) -->
            <div class="mt-6 bg-slate-900/40 border border-slate-800/80 rounded-xl p-4">
                <h3 class="text-xs font-bold text-purple-400 gaming-font uppercase tracking-wider mb-3">// VEHICLE_DIAGNOSTIC_RECORD (REKAM MEDIS)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">Nama Pemilik</span>
                        <span class="font-semibold text-white uppercase">{{ $service->customer->name }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">No. Plat Nomor</span>
                        <span class="font-mono font-bold text-cyan-400 uppercase">{{ $service->customer->license_plate }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">Tipe Motor</span>
                        <span class="font-semibold text-white uppercase">{{ $service->customer->motorcycle_type }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">Kapasitas Mesin</span>
                        <span class="font-mono text-slate-300">{{ $service->customer->engine_capacity ?? '-' }} CC</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">Nomor Rangka STNK</span>
                        <span class="font-mono text-slate-300 uppercase">{{ $service->customer->frame_number ?? '// NOT_LOGGED' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="block text-slate-500 text-[10px] uppercase font-mono">Nomor Mesin STNK</span>
                        <span class="font-mono text-slate-300 uppercase">{{ $service->customer->engine_number ?? '// NOT_LOGGED' }}</span>
                    </div>
                </div>
            </div>

            <!-- DETAILED LOOT & ACTIONS (DAFTAR SPAREPART DAN TINDAKAN) -->
            <div class="mt-6">
                <h3 class="text-xs font-bold text-cyan-400 gaming-font uppercase tracking-wider mb-3">// ACTION_AND_LOOT_DETAILS</h3>
                <table class="w-full text-left text-xs font-mono">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-500 uppercase text-[10px]">
                            <th class="py-2">Deskripsi Tindakan / Suku Cadang</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Harga Unit</th>
                            <th class="py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900 text-slate-300">
                        @foreach($service->details as $detail)
                        <tr>
                            <td class="py-3">
                                <span class="font-medium text-slate-200">{{ $detail->action_name }}</span>
                                @if($detail->item_id)
                                    <span class="block text-[10px] text-slate-500">Code: {{ $detail->item->item_code }} (Stok otomatis terpotong)</span>
                                @endif
                            </td>
                            <td class="py-3 text-center text-cyan-400 font-bold">{{ $detail->quantity }}</td>
                            <td class="py-3 text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="py-3 text-right text-slate-200">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- CALCULATION HUD -->
            <div class="mt-6 border-t border-slate-800 pt-4 flex flex-col items-end font-mono text-xs">
                <div class="w-full sm:w-64 space-y-2">
                    <div class="flex justify-between text-slate-400">
                        <span>TOTAL COST:</span>
                        <span class="font-bold text-white">Rp {{ number_format($service->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>CASH PAID:</span>
                        <span class="font-bold text-emerald-400">Rp {{ number_format($service->amount_paid ?? $service->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-900 pt-2 text-sm font-bold text-cyan-400">
                        <span>CHANGE:</span>
                        <span>Rp {{ number_format($service->change ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- FOOTER SIGNATURE -->
            <div class="mt-8 pt-6 border-t border-slate-900 text-center text-[10px] text-slate-500 font-mono grid grid-cols-3">
                <div>
                    <p>Mekanik Assigned</p>
                    <p class="mt-6 text-slate-300 uppercase font-bold">{{ $service->mechanic->name ?? 'None' }}</p>
                </div>
                <div>
                    <p>Kasir/Merchant</p>
                    <p class="mt-6 text-slate-300 uppercase font-bold">{{ $service->cashier->name ?? auth()->user()->name }}</p>
                </div>
                <div>
                    <p>Pelanggan</p>
                    <p class="mt-6 text-slate-300 uppercase font-bold">{{ $service->customer->name }}</p>
                </div>
            </div>
        </div>

        <!-- HISTORIS REKAM MEDIS MASA LALU (TIDAK IKUT DICETAK DI NOTA) -->
        @if($vehicleHistory->count() > 0)
        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-6 shadow-xl no-print">
            <h3 class="text-xs font-bold text-amber-500 gaming-font uppercase tracking-wider mb-3">📋 HISTORICAL_MEDICAL_LOG (Rekam Medis Masa Lalu)</h3>
            <div class="space-y-4">
                @foreach($vehicleHistory as $history)
                    <div class="bg-slate-900/40 border border-slate-800 rounded-xl p-3 text-xs font-mono">
                        <div class="flex justify-between text-[11px] text-slate-400 border-b border-slate-800/60 pb-1 mb-2">
                            <span>Quest: <strong class="text-cyan-400">{{ $history->queue_number }}</strong></span>
                            <span>Tanggal: {{ $history->created_at->format('d M Y') }}</span>
                        </div>
                        <ul class="list-disc list-inside text-slate-300 space-y-1">
                            @foreach($history->details as $hDetail)
                                <li>{{ $hDetail->action_name }} ({{ $hDetail->quantity }} pcs)</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</body>
</html>