<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PantauPangan Plus - Sistem Ketahanan Pangan</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 font-sans antialiased text-gray-100 min-h-screen flex flex-col justify-between relative bg-cover bg-center" 
    style="background-image: url('https://lh6.googleusercontent.com/proxy/C65XB0f4xkNLMcBqQM5e4Kc6ZxGnzRcGzDD-0QvZaLk5k2Uau2oWcF-Or9W3WiB-d7U9v-ip9RACLiw5nTFYHFdFAGxZRqbp5WVS')">
    
    <div class="absolute inset-0 bg-black/65 backdrop-blur-xs z-0"></div>

    <nav class="relative z-10 bg-white/10 backdrop-blur-md border-b border-white/10 px-6 py-4 md:px-12 flex justify-between items-center shadow-lg">
        <div class="flex items-center space-x-2">
            <span class="text-2xl">🌾</span>
            <h1 class="text-xl md:text-2xl font-extrabold tracking-wide bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">
                PantauPangan <span class="text-white">Plus</span>
            </h1>
        </div>
        <div class="flex items-center space-x-3 md:space-x-6">
            <a href="login.php" class="text-gray-200 hover:text-green-400 font-medium transition duration-200 text-sm md:text-base">
                Masuk
            </a>
            <a href="register.php" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:shadow-green-900/50 transition duration-300 text-sm md:text-base transform hover:-translate-y-0.5">
                Daftar Akun
            </a>
        </div>
    </nav>

    <main class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-6 max-w-4xl mx-auto my-12">
        <div class="inline-flex items-center space-x-2 bg-green-500/20 text-green-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider border border-green-500/30 mb-6 backdrop-blur-sm animate-pulse">
            <span>Multi-Role Dashboard Terintegrasi</span>
        </div>

        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6 drop-shadow-md">
            Sistem Monitoring <br>
            <span class="bg-gradient-to-r from-green-400 via-emerald-400 to-teal-400 bg-clip-text text-transparent">
                Ketersediaan Pangan Real-Time
            </span>
        </h2>
        
        <p class="text-gray-300 text-base md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed drop-shadow">
            Pantau pergerakan harga komoditas pasar, kelola distribusi logistik, dan amankan pasokan pangan nasional melalui satu platform yang transparan, cerdas, dan responsif.
        </p>

        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
            <a href="register.php" class="bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-400 hover:to-emerald-400 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-green-600/30 hover:shadow-green-500/40 transition duration-300 transform hover:-translate-y-1 text-center">
                Mulai Pantau Sekarang
            </a>
            <a href="login.php" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-8 py-4 rounded-xl font-bold backdrop-blur-md transition duration-300 transform hover:-translate-y-1 text-center">
                Masuk ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mt-16 text-left">
            <div class="bg-white/5 border border-white/10 p-5 rounded-xl backdrop-blur-xs">
                <div class="text-xl mb-2">🛡️</div>
                <h4 class="font-bold text-white mb-1">Masyarakat</h4>
                <p class="text-xs text-gray-400">Akses info harga pasar transparan, akurat, dan harian.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-5 rounded-xl backdrop-blur-xs">
                <div class="text-xl mb-2">🚜</div>
                <h4 class="font-bold text-white mb-1">Petugas Lapangan</h4>
                <p class="text-xs text-gray-400">Input pasokan komoditas langsung dari lapangan.</p>
            </div>
            <div class="bg-white/5 border border-white/10 p-5 rounded-xl backdrop-blur-xs">
                <div class="text-xl mb-2">⚙️</div>
                <h4 class="font-bold text-white mb-1">Admin Sistem</h4>
                <p class="text-xs text-gray-400">Manajemen akun pengguna dan kendali penuh data.</p>
            </div>
        </div>
    </main>

    <footer class="relative z-10 bg-black/40 backdrop-blur-md border-t border-white/5 text-center py-4 text-xs text-gray-500">
        &copy; 2026 PantauPangan Plus. Semua Hak Dilindungi.
    </footer>

</body>
</html>