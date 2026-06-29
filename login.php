<?php
include 'config.php';
session_start();
$pesan = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) === 1) {
            $row = $result[0];
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['login'] = true;
                $_SESSION['nama']  = $row['nama'];
                $_SESSION['role']  = $row['role'];
                
                header("Location: dashboard.php");
                exit;
            }
        }
        $pesan = "<div class='bg-red-500/20 border border-red-500/50 text-red-200 p-2 rounded text-sm mb-4 text-center'>Email atau Password salah!</div>";
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
    <title>Login - PantauPangan Plus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center relative bg-cover bg-center px-4" 
      style="background-image: url('https://lh6.googleusercontent.com/proxy/C65XB0f4xkNLMcBqQM5e4Kc6ZxGnzRcGzDD-0QvZaLk5k2Uau2oWcF-Or9W3WiB-d7U9v-ip9RACLiw5nTFYHFdFAGxZRqbp5WVS')">
    
    <div class="absolute inset-0 bg-black/65 backdrop-blur-xs z-0"></div>

    <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/10 p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-6">
            <span class="text-3xl">🌾</span>
            <h2 class="text-2xl font-black bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent mt-2">PantauPangan Plus</h2>
            <p class="text-gray-300 text-sm mt-1">Masuk untuk memantau kondisi pangan</p>
        </div>
        
        <?= $pesan ?>
        
        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-200">Email</label>
                <input type="email" name="email" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 focus:bg-white/10 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200">Password</label>
                <input type="password" name="password" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 focus:bg-white/10 transition">
            </div>
            <button type="submit" name="login" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-400 hover:to-emerald-400 text-white p-2.5 rounded-xl font-bold shadow-lg shadow-green-600/20 transition transform hover:-translate-y-0.5 cursor-pointer mt-2">Masuk</button>
        </form>
        
        <p class="text-sm text-center text-gray-300 mt-6">Belum punya akun? <a href="register.php" class="text-green-400 font-bold hover:underline">Daftar Sekarang</a></p>
    </div>
</body>
</html>