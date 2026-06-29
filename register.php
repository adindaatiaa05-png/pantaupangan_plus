<?php
include 'config.php';
$pesan = "";

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    try {
        // Cek email sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $cek_email = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($cek_email) > 0) {
            $pesan = "<div class='bg-red-500/20 border border-red-500/50 text-red-200 p-2 rounded text-sm mb-4 text-center'>Email sudah terdaftar!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nama, $email, $password, $role])) {
                header("Location: login.php");
            } else {
                $pesan = "<div class='bg-red-500/20 border border-red-500/50 text-red-200 p-2 rounded text-sm mb-4 text-center'>Gagal mendaftar.</div>";
            }
        }
    } catch (PDOException $e) {
        $pesan = "<div class='bg-red-500/20 border border-red-500/50 text-red-200 p-2 rounded text-sm mb-4 text-center'>Terjadi kesalahan. Coba lagi nanti.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PantauPangan Plus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative bg-cover bg-center px-4 py-8" 
      style="background-image: url('https://lh6.googleusercontent.com/proxy/C65XB0f4xkNLMcBqQM5e4Kc6ZxGnzRcGzDD-0QvZaLk5k2Uau2oWcF-Or9W3WiB-d7U9v-ip9RACLiw5nTFYHFdFAGxZRqbp5WVS')">
    
    <div class="absolute inset-0 bg-black/65 backdrop-blur-xs z-0"></div>

    <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/10 p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-6">
            <span class="text-3xl">🌾</span>
            <h2 class="text-2xl font-black bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent mt-2">Buat Akun Baru</h2>
            <p class="text-gray-300 text-sm mt-1">Gabung dalam ekosistem ketahanan pangan</p>
        </div>
        
        <?= $pesan ?>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-200">Nama Lengkap</label>
                <input type="text" name="nama" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 focus:bg-white/10 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200">Email</label>
                <input type="email" name="email" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 focus:bg-white/10 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200">Password</label>
                <input type="password" name="password" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 focus:bg-white/10 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200">Pilih Role</label>
                <select name="role" class="w-full mt-1 p-2.5 bg-gray-800/80 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 transition">
                    <option value="masyarakat" class="bg-gray-800 text-white">Masyarakat</option>
                    <option value="petugas" class="bg-gray-800 text-white">Petugas Lapangan</option>
                    <option value="admin" class="bg-gray-800 text-white">Admin Sistem</option>
                </select>
            </div>
            <button type="submit" name="register" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-400 hover:to-emerald-400 text-white p-2.5 rounded-xl font-bold shadow-lg shadow-green-600/20 transition transform hover:-translate-y-0.5 cursor-pointer mt-2">Daftar Akun</button>
        </form>
        
        <p class="text-sm text-center text-gray-300 mt-6">Sudah punya akun? <a href="login.php" class="text-green-400 font-bold hover:underline">Masuk di sini</a></p>
    </div>
</body>
</html>