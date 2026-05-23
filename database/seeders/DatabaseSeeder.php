<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. SEEDING CHARACTERS (USERS & ROLES)
        // ==========================================
        
        $owner = User::create([
            'name' => 'Agung (Guild Master)',
            'email' => 'owner@fainaya.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        $cashier = User::create([
            'name' => 'Siti (Merchant)',
            'email' => 'cashier@fainaya.com',
            'password' => Hash::make('password123'),
            'role' => 'cashier',
        ]);

        $mechanic1 = User::create([
            'name' => 'Cecep (Combatant 01)',
            'email' => 'mechanic1@fainaya.com',
            'password' => Hash::make('password123'),
            'role' => 'mechanic',
        ]);

        $mechanic2 = User::create([
            'name' => 'Asep (Combatant 02)',
            'email' => 'mechanic2@fainaya.com',
            'password' => Hash::make('password123'),
            'role' => 'mechanic',
        ]);

        User::create([
            'name' => 'Budi (Inventory Manager)',
            'email' => 'warehouse@fainaya.com',
            'password' => Hash::make('password123'),
            'role' => 'warehouse',
        ]);


        // ==========================================
        // 2. SEEDING LOOT INVENTORY (SPAREPARTS)
        // ==========================================
        
        $oliMpx2 = Item::create([
            'item_code' => 'OLI-MPX2-08L',
            'name' => 'Oli Astra Honda Motor MPX2 0.8L',
            'stock' => 45,
            'min_stock' => 10,
            'purchase_price' => 42000.00,
            'sell_price' => 52000.00,
        ]);

        $vanBelt = Item::create([
            'item_code' => 'VBLT-VAR150',
            'name' => 'Van Belt Kit Vario 150 Honda',
            'stock' => 3, // Set sengaja menipis untuk memicu 'Critical Alert/Low Health'
            'min_stock' => 5,
            'purchase_price' => 115000.00,
            'sell_price' => 140000.00,
        ]);

        $busiDenso = Item::create([
            'item_code' => 'BSI-DNS-U27',
            'name' => 'Busi Denso U27EPR-9',
            'stock' => 80,
            'min_stock' => 15,
            'purchase_price' => 13000.00,
            'sell_price' => 20000.00,
        ]);

        $kampasRem = Item::create([
            'item_code' => 'KMP-FR-NMAX',
            'name' => 'Kampas Rem Depan NMAX Yamaha',
            'stock' => 12,
            'min_stock' => 5,
            'purchase_price' => 55000.00,
            'sell_price' => 75000.00,
        ]);


        // ==========================================
        // 3. SEEDING CUSTOMERS & ACTIVE QUESTS
        // ==========================================
        
        // --- DATA ANT REAN 1: STATUS 'QUEUE' (BARU DATANG) ---
        $cust1 = Customer::create([
            'name' => 'Rian Hidayat',
            'phone' => '081234567890',
            'license_plate' => 'D 4432 CE',
            'motorcycle_type' => 'Honda Beat FI',
        ]);

        Service::create([
            'customer_id' => $cust1->id,
            'mechanic_id' => null,
            'cashier_id' => null,
            'queue_number' => 'REG-001',
            'status' => 'queue',
            'total_price' => 0,
        ]);

        // --- DATA ANTREAN 2: STATUS 'PROCESSING' (SEDANG DIKERJAKAN) ---
        $cust2 = Customer::create([
            'name' => 'Indra Wijaya',
            'phone' => '085712345678',
            'license_plate' => 'D 6789 BAF',
            'motorcycle_type' => 'Yamaha NMAX 155',
        ]);

        $service2 = Service::create([
            'customer_id' => $cust2->id,
            'mechanic_id' => $mechanic1->id, // Dikerjakan Cecep
            'cashier_id' => null,
            'queue_number' => 'REG-002',
            'status' => 'processing',
            'total_price' => 110000.00, // Akumulasi biaya sementara
        ]);

        // Detail tindakan/item yang digunakan di Antrean 2
        ServiceDetail::create([
            'service_id' => $service2->id,
            'item_id' => null, // Jasa murni
            'action_name' => 'Jasa Servis Ringan & Pembersihan CVT',
            'quantity' => 1,
            'price' => 35000.00,
            'subtotal' => 35000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service2->id,
            'item_id' => $kampasRem->id, // Menggunakan Sparepart
            'action_name' => 'Ganti Kampas Rem Depan NMAX',
            'quantity' => 1,
            'price' => 75000.00,
            'subtotal' => 75000.00,
        ]);

        // --- DATA ANTREAN 3: STATUS 'DONE' (SELESAI, BELUM BAYAR) ---
        $cust3 = Customer::create([
            'name' => 'Dewi Lestari',
            'phone' => '089987654321',
            'license_plate' => 'D 2112 ZZ',
            'motorcycle_type' => 'Honda Vario 150',
        ]);

        $service3 = Service::create([
            'customer_id' => $cust3->id,
            'mechanic_id' => $mechanic2->id, // Dikerjakan Asep
            'cashier_id' => null,
            'queue_number' => 'REG-003',
            'status' => 'done',
            'total_price' => 222000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service3->id,
            'item_id' => null,
            'action_name' => 'Jasa Ganti Oli & Drive Belt',
            'quantity' => 1,
            'price' => 30000.00,
            'subtotal' => 30000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service3->id,
            'item_id' => $oliMpx2->id,
            'action_name' => 'Oli Astra Honda Motor MPX2 0.8L',
            'quantity' => 1,
            'price' => 52000.00,
            'subtotal' => 52000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service3->id,
            'item_id' => $vanBelt->id,
            'action_name' => 'Van Belt Kit Vario 150 Honda',
            'quantity' => 1,
            'price' => 140000.00,
            'subtotal' => 140000.00,
        ]);

        // --- DATA ANTREAN 4: STATUS 'PAID' (SUDAH BAYAR / SINKRON ARSIP) ---
        $cust4 = Customer::create([
            'name' => 'Ahmad Fauzi',
            'phone' => '082155667788',
            'license_plate' => 'D 3310 ABC',
            'motorcycle_type' => 'Honda Supra X 125',
        ]);

        $service4 = Service::create([
            'customer_id' => $cust4->id,
            'mechanic_id' => $mechanic1->id,
            'cashier_id' => $cashier->id, // Diselesaikan oleh kasir Siti
            'queue_number' => 'REG-004',
            'status' => 'paid',
            'total_price' => 55000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service4->id,
            'item_id' => null,
            'action_name' => 'Jasa Bersihkan Karburator & Setel Rantai',
            'quantity' => 1,
            'price' => 35000.00,
            'subtotal' => 35000.00,
        ]);

        ServiceDetail::create([
            'service_id' => $service4->id,
            'item_id' => $busiDenso->id,
            'action_name' => 'Busi Denso U27EPR-9',
            'quantity' => 1,
            'price' => 20000.00,
            'subtotal' => 20000.00,
        ]);
    }
}