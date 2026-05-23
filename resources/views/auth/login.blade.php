<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fainaya Motor - Authenticate Gate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #060913; }
        .cyber-font { font-family: 'Orbitron', sans-serif; }
        .cyber-panel {
            box-shadow: 0 0 25px rgba(236, 72, 153, 0.15), inset 0 0 15px rgba(236, 72, 153, 0.05);
            border: 1px solid rgba(236, 72, 153, 0.25);
        }
        .cyber-input:focus {
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
            border-color: rgb(6, 182, 212);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-950 via-slate-950 to-black">

    <div class="w-full max-w-md">
        <!-- LOGO HEADER -->
        <div class="text-center mb-8">
            <h1 class="cyber-font text-3xl font-black tracking-widest bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-400 bg-clip-text text-transparent">
                FAINAYA_MOTOR
            </h1>
            <p class="text-xs text-slate-500 tracking-widest uppercase mt-1 font-mono">// ENTER_ACCESS_KEY_TO_INITIALIZE</p>
        </div>

        <!-- LOGIN FORM PANEL -->
        <div class="bg-slate-900/40 backdrop-blur-md rounded-2xl p-8 cyber-panel">
            <div class="mb-6 border-b border-slate-800 pb-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300 cyber-font">User Authentication</h2>
            </div>

            <!-- Tampilkan Error Jika Kredensial Salah -->
            @if ($errors->any())
                <div class="mb-4 bg-rose-500/10 border border-rose-500/30 rounded-lg p-3 text-xs text-rose-400 font-mono">
                    @foreach ($errors->all() as $error)
                        <p>⚠️ ERROR: {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- EMAIL / IDENTIFIER -->
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-2 cyber-font">Email Node</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="name@fainaya.com"
                        class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-3 text-sm text-slate-200 outline-none transition-all cyber-input">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-2 cyber-font">Secure Key</label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-3 text-sm text-slate-200 outline-none transition-all cyber-input">
                </div>

                <!-- REMEMBER ME CHECKBOX -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 text-slate-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="accent-pink-500 h-4 w-4 bg-slate-950 border-slate-800 rounded">
                        <span>Keep Session Alive</span>
                    </label>
                </div>

                <!-- INITIATE BUTTON -->
                <button type="submit" 
                    class="w-full mt-2 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-400 hover:to-purple-500 text-slate-950 text-xs font-black uppercase tracking-widest py-3.5 rounded-lg transition-all duration-300 cyber-font shadow-[0_0_15px_rgba(236,72,153,0.3)] hover:shadow-[0_0_25px_rgba(236,72,153,0.5)]">
                    Connect to System
                </button>
            </form>
        </div>

        <!-- MOCK ACCOUNTS FOOTNOTE FOR TESTING -->
        <div class="mt-6 text-center text-[10px] text-slate-600 font-mono">
            <p>Pintu uji coba seeder aktif. Gunakan akun contoh:</p>
            <p class="mt-1 text-slate-500">owner@fainaya.com / mechanic1@fainaya.com (Sandi: password123)</p>
        </div>
    </div>

</body>
</html>