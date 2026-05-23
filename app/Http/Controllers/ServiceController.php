<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ServiceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    // 1. LIST QUEUE & ACTIVE QUESTS
    public function index()
    {
        // Menampilkan list antrean aktif di Dashboard Gaming
        $services = Service::with(['customer', 'mechanic'])
            ->orderByRaw("FIELD(status, 'processing', 'queue', 'done', 'paid')")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('services.index', compact('services'));
    }

    /**
     * 2A. VIEW REGISTRASI KASIR (MERCHANT STATION)
     */
    public function create()
    {
        // Mengambil data antrean hari ini untuk visualisasi konter di kasir
        $todayQueueCount = Service::whereDate('created_at', today())->count();
        
        // Mengambil list plat nomor terdaftar untuk fitur auto-complete bawaan browser (optional)
        $registeredPlates = Customer::select('license_plate', 'name', 'motorcycle_type')->get();

        return view('services.create', compact('todayQueueCount', 'registeredPlates'));
    }

    /**
     * 2B. REGISTER NEW QUEUE (KASIR / MERCHANT ACTION)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:15',
            'motorcycle_type' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);
        // Inisialisasi variabel untuk menampung nomor antrean hasil generate
        $generatedQueue = null;

        DB::transaction(function () use ($request, &$generatedQueue) {
            $cleanPlate = strtoupper(str_replace(' ', '', $request->license_plate));
            // Cek atau Daftarkan Data Customer Baru
            $customer = Customer::firstOrCreate(
                ['license_plate' => $cleanPlate],
                [
                    'name' => $request->name,
                    'motorcycle_type' => strtoupper($request->motorcycle_type),
                    'phone' => $request->phone ?? '-'
                ]
            );

            // Generate Nomor Antrean Hari Ini
            $todayCount = Service::whereDate('created_at', today())->lockForUpdate()->count();
            
            // Format Otomatis: REG-001, REG-002, dst.
            $generatedQueue = 'REG-' . str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);

            // Insert ke tabel Utama Services
            Service::create([
                'customer_id' => $customer->id,
                'queue_number' => $generatedQueue,
                'status' => 'queue',
                'total_price' => 0
            ]);
        });

        return redirect()->route('services.index')->with('success', 'New Quest Registered Successfully!');
    }

    // 3. START PROGRESS (MEKANIK ACTION)
    public function startProcessing(Service $service)
    {
        if ($service->status !== 'queue') {
            return back()->with('error', 'Quest is already in progress or completed.');
        }

        $service->update([
            'mechanic_id' => Auth::id(), // Mekanik yang menekan tombol login otomatis terikat
            'status' => 'processing'
        ]);

        return back()->with('success', 'Quest Started! Keep grinding, Mech!');
    }

    // 4. ADD ACTION / SPAREPART (MEKANIK ACTION)
    public function addAction(Request $request, Service $service)
    {
        $request->validate([
            'action_name' => 'required|string|max:255',
            'item_id' => 'nullable|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $service) {
            $subtotal = $request->price * $request->quantity;

            // Jika mekanik menginput penggantian sparepart, kurangi stok item gudang
            if ($request->item_id) {
                $item = Item::lockForUpdate()->find($request->item_id);
                
                if ($item->stock < $request->quantity) {
                    throw new \Exception("Insufficient inventory stock for item: {$item->name}");
                }

                $item->decrement('stock', $request->quantity);
            }

            // Catat detail tindakan ke baris detail transaksi
            ServiceDetail::create([
                'service_id' => $service->id,
                'item_id' => $request->item_id,
                'action_name' => $request->action_name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'subtotal' => $subtotal,
            ]);

            // Hitung ulang total biaya pengerjaan sementara
            $service->increment('total_price', $subtotal);
        });

        return back()->with('success', 'Loot/Action added to active task!');
    }

    // 5. FINISH REPAIR (MEKANIK ACTION)
    public function completeService(Service $service)
    {
        if ($service->status !== 'processing') {
            return back()->with('error', 'Cannot complete a service that is not in progress.');
        }

        $service->update(['status' => 'done']);

        return redirect()->route('mechanic.index')->with('success', 'Quest Clear! Waiting for customer checkout.');
    }

    // 6. CHECKOUT & PAYMENT (KASIR / MERCHANT ACTION)
    public function pay(Request $request, Service $service)
    {
        if ($service->status !== 'done') {
            return back()->with('error', 'Cannot settle payment. Service task is not finished yet.');
        }

        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $service->total_price,
        ]);

        DB::transaction(function () use ($request, $service) {
            $change = $request->amount_paid - $service->total_price;

            $service->update([
                'cashier_id'  => Auth::id(),
                'status'      => 'paid',
                'amount_paid' => $request->amount_paid,
                'change'      => $change,
                'paid_at'     => now()
            ]);
        });

        // Langsung arahkan ke halaman cetak nota setelah sukses checkout
        return redirect()->route('services.invoice', $service->id)
            ->with('success', 'Gold Received! Settle Payment Clear. Printing Invoice...');
    }

    /**
     * 6B. INVOICE HUD & REKAM MEDIS VIEW (PRINT READY)
     */
    public function invoice(Service $service)
    {
        // Pastikan data yang diakses sudah lunas atau minimal selesai dikerjakan
        if (!in_array($service->status, ['paid', 'done'])) {
            return redirect()->route('services.index')->with('error', 'Invoice locked until quest completed.');
        }

        // Load semua relasi untuk menyusun rekam medis kendaraan menyeluruh
        $service->load(['customer', 'mechanic', 'cashier', 'details.item']);

        // Mengambil riwayat servis masa lalu dari kendaraan ini (Rekam Medis Historis)
        $vehicleHistory = Service::where('customer_id', $service->customer_id)
            ->where('id', '!=', $service->id)
            ->where('status', 'paid')
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('services.invoice', compact('service', 'vehicleHistory'));
    }

    // 7. DETAIL VIEW (Fungsi melihat rekaman data)
    public function show(Service $service)
    {
        $service->load(['customer', 'mechanic', 'cashier', 'details.item']);
        return view('services.show', compact('service'));
    }

    /**
     * UPDATE DATA STNK DARI UNIT KENDARAAN (PROSES PEMBONGKARAN)
     */
    public function updateStnk(Request $request, Service $service)
    {
        // Validasi data STNK fisik
        $request->validate([
            'frame_number'    => 'required|string|max:50',
            'engine_number'   => 'required|string|max:50',
            'engine_capacity' => 'required|integer|min:50|max:2000', // Batasan CC Motor standar
        ]);

        // Update langsung ke data pelanggan yang terikat dengan servis ini
        $service->customer->update([
            'frame_number'    => strtoupper(str_replace(' ', '', $request->frame_number)),
            'engine_number'   => strtoupper(str_replace(' ', '', $request->engine_number)),
            'engine_capacity' => $request->engine_capacity,
        ]);

        return redirect()->back()->with('success', 'STNK Data Logged Successfully! Vehicle identity verified.');
    }
}