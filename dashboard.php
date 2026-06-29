<?php
include 'config.php';
session_start();

// Proteksi Halaman: Jika belum login, tendang ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
$pesan = "";

// ==========================================
// FITUR 1: CREATE (TAMBAH DATA PANGAN)
// ==========================================
if (isset($_POST['tambah_pangan']) && ($role == 'petugas' || $role == 'admin')) {
    $komoditas = $_POST['nama_komoditas'];
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $status = $_POST['status'];

    try {
        $stmt = $conn->prepare("INSERT INTO pangan (nama_komoditas, harga, stok, status, diupdate_oleh) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$komoditas, $harga, $stok, $status, $nama])) {
            $pesan = "<div class='bg-green-500/20 border border-green-500/30 text-green-200 p-3 rounded-xl mb-4'>Data pangan berhasil ditambahkan!</div>";
        } else {
            $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Gagal menambah data.</div>";
        }
    } catch (PDOException $e) {
        $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Gagal menambah data.</div>";
    }
}

// ==========================================
// FITUR 2: UPDATE (MENGUBAH DATA PANGAN - TUGAS MANDIRI)
// ==========================================
if (isset($_POST['update_pangan']) && ($role == 'petugas' || $role == 'admin')) {
    $id_pangan = intval($_POST['id_pangan']);
    $komoditas = $_POST['nama_komoditas'];
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $status = $_POST['status'];

    try {
        $cek = $conn->prepare("SELECT id FROM pangan WHERE id = ?");
        $cek->execute([$id_pangan]);
        if ($cek->rowCount() > 0) {
            $stmt = $conn->prepare("UPDATE pangan SET nama_komoditas=?, harga=?, stok=?, status=?, diupdate_oleh=? WHERE id=?");
            $stmt->execute([$komoditas, $harga, $stok, $status, $nama, $id_pangan]);
            $pesan = "<div class='bg-green-500/20 border border-green-500/30 text-green-200 p-3 rounded-xl mb-4'>Data berhasil diperbarui!</div>";
        } else {
            http_response_code(404);
            $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Error 404: Komoditas tidak ditemukan.</div>";
        }
    } catch (PDOException $e) {
        $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Error: Gagal memperbarui data.</div>";
    }
}

// ==========================================
// FITUR 3: DELETE (HAPUS DATA PANGAN - TUGAS MANDIRI)
// ==========================================
if (isset($_GET['hapus_pangan']) && ($role == 'petugas' || $role == 'admin')) {
    $id_hapus = intval($_GET['hapus_pangan']);
    try {
        $cek = $conn->prepare("SELECT id FROM pangan WHERE id = ?");
        $cek->execute([$id_hapus]);
        if ($cek->rowCount() > 0) {
            $stmt = $conn->prepare("DELETE FROM pangan WHERE id = ?");
            $stmt->execute([$id_hapus]);
            header("Location: dashboard.php?tab=dashboard");
            exit;
        } else {
            http_response_code(404);
            $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Error 404: Data gagal dihapus karena tidak ada.</div>";
        }
    } catch (PDOException $e) {
        $pesan = "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl mb-4'>Error: Gagal menghapus data.</div>";
    }
}

// ==========================================
// FITUR ADMIN: HAPUS USER
// ==========================================
if (isset($_GET['hapus_user']) && $role == 'admin') {
    $id_hapus = intval($_GET['hapus_user']);
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id_hapus]);
        header("Location: dashboard.php?tab=manajemen");
        exit;
    } catch (PDOException $e) {
        // Handle error silently
    }
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PantauPangan Plus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-950 flex h-screen font-sans text-gray-100 relative bg-cover bg-center"
      style="background-image: url('https://lh6.googleusercontent.com/proxy/C65XB0f4xkNLMcBqQM5e4Kc6ZxGnzRcGzDD-0QvZaLk5k2Uau2oWcF-Or9W3WiB-d7U9v-ip9RACLiw5nTFYHFdFAGxZRqbp5WVS');">
    
    <div class="absolute inset-0 bg-black/75 backdrop-blur-xs z-0"></div>

    <div class="relative z-10 w-64 bg-white/5 backdrop-blur-md border-r border-white/10 flex flex-col justify-between p-6">
        <div>
            <div class="flex items-center space-x-2 mb-8">
                <span class="text-xl">🌾</span>
                <h2 class="text-lg font-black bg-gradient-to-r from-green-400 to-emerald-400 bg-clip-text text-transparent">PantauPangan+</h2>
            </div>
            <nav class="space-y-2">
                <a href="dashboard.php?tab=dashboard" class="block p-3 rounded-xl transition <?= $tab == 'dashboard' ? 'bg-green-600 text-white font-bold' : 'hover:bg-white/5 text-gray-300' ?>">📊 Main Dashboard</a>
                
                <?php if($role == 'admin'): ?>
                    <a href="dashboard.php?tab=manajemen" class="block p-3 rounded-xl transition <?= $tab == 'manajemen' ? 'bg-green-600 text-white font-bold' : 'hover:bg-white/5 text-gray-300' ?>">👥 Manajemen User</a>
                <?php endif; ?>
                
                <?php if($role == 'admin' || $role == 'petugas'): ?>
                    <a href="dashboard.php?tab=input" class="block p-3 rounded-xl transition <?= $tab == 'input' ? 'bg-green-600 text-white font-bold' : 'hover:bg-white/5 text-gray-300' ?>">📝 Input Data Pangan</a>
                <?php endif; ?>
            </nav>
        </div>
        <div>
            <a href="logout.php" class="block text-center bg-red-600/80 hover:bg-red-600 text-white p-2.5 rounded-xl transition font-semibold shadow-lg">🚪 Logout</a>
        </div>
    </div>

    <div class="relative z-10 flex-1 p-10 overflow-y-auto">
        <header class="flex justify-between items-center mb-8 border-b border-white/10 pb-4">
            <div>
                <h1 class="text-3xl font-black text-white">Dashboard Beranda</h1>
                <p class="text-gray-400 text-sm mt-1">Selamat datang, <span class="text-green-400 font-semibold"><?= htmlspecialchars($nama) ?></span></p>
            </div>
            <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 font-bold px-4 py-1.5 rounded-full text-xs uppercase tracking-wider">Akses: <?= $role ?></span>
        </header>

        <?= $pesan ?>

        <?php if ($tab == 'dashboard'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-sm">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Sistem</h3>
                    <p class="text-2xl font-black text-green-400 mt-2">Aktif</p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-sm">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Komoditas Dipantau</h3>
                    <p class="text-2xl font-black text-white mt-2"><?php $count_pangan = $conn->query("SELECT COUNT(*) FROM pangan")->fetch(); echo $count_pangan[0] . " Item"; ?></p>
                </div>
                <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-sm">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">User Terdaftar</h3>
                    <p class="text-2xl font-black text-white mt-2"><?php $count_users = $conn->query("SELECT COUNT(*) FROM users")->fetch(); echo $count_users[0] . " Akun"; ?></p>
                </div>
            </div>

            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-bold text-white mb-4">📋 Tabel Pemantauan Pangan Terkini</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-gray-400 text-sm uppercase">
                                <th class="p-3">Komoditas</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Stok (Ton)</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Pengupdate</th>
                                <?php if($role == 'admin' || $role == 'petugas'): ?>
                                    <th class="p-3 text-center">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-gray-200">
                            <?php
                            $data_pangan = $conn->query("SELECT * FROM pangan ORDER BY id DESC");
                            while($p = $data_pangan->fetch(PDO::FETCH_ASSOC)):
                                $warna_status = $p['status'] == 'Aman' ? 'bg-green-500/20 text-green-300 border-green-500/30' : ($p['status'] == 'Waspada' ? 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30' : 'bg-red-500/20 text-red-300 border-red-500/30');
                            ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="p-3 font-semibold text-white"><?= htmlspecialchars($p['nama_komoditas']) ?></td>
                                <td class="p-3">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                <td class="p-3"><?= number_format($p['stok']) ?></td>
                                <td class="p-3"><span class="border px-2 py-0.5 rounded-lg text-xs font-bold <?= $warna_status ?>"><?= $p['status'] ?></span></td>
                                <td class="p-3 text-sm text-gray-400"><?= htmlspecialchars($p['diupdate_oleh']) ?></td>
                                
                                <?php if($role == 'admin' || $role == 'petugas'): ?>
                                    <td class="p-3 text-center space-x-2">
                                        <a href="dashboard.php?tab=edit&id_edit=<?= $p['id'] ?>" class="text-blue-400 hover:text-blue-300 font-bold text-sm transition">✏️ Edit</a>
                                        <a href="dashboard.php?hapus_pangan=<?= $p['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data pangan ini?')" class="text-red-400 hover:text-red-300 font-bold text-sm transition">❌ Hapus</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab == 'input' && ($role == 'petugas' || $role == 'admin')): ?>
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-lg max-w-lg">
                <h2 class="text-xl font-bold text-white mb-4">✍️ Form Input Kondisi Pangan</h2>
                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nama Komoditas</label>
                        <input type="text" name="nama_komoditas" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Harga Per Satuan (Rp)</label>
                        <input type="number" name="harga" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Stok (Angka)</label>
                        <input type="number" name="stok" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Status Ketersediaan</label>
                        <select name="status" class="w-full mt-1 p-2.5 bg-gray-800 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                            <option value="Aman">Aman</option>
                            <option value="Waspada">Waspada</option>
                            <option value="Langka">Langka</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_pangan" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white p-2.5 rounded-xl font-bold hover:shadow-lg transition cursor-pointer">Simpan Data</button>
                </form>
            </div>
        <?php endif; ?>

        <?php 
        if ($tab == 'edit' && ($role == 'petugas' || $role == 'admin')): 
            $id_edit = intval($_GET['id_edit']);
            $ambil_pangan = $conn->prepare("SELECT * FROM pangan WHERE id = ?");
            $ambil_pangan->execute([$id_edit]);
            $pangan_data = $ambil_pangan->fetchAll(PDO::FETCH_ASSOC);
            if(count($pangan_data) > 0):
                $ep = $pangan_data[0];
        ?>
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-lg max-w-lg">
                <h2 class="text-xl font-bold text-white mb-4">✏️ Ubah Data Pangan (ID: <?= $ep['id'] ?>)</h2>
                <form action="dashboard.php?tab=dashboard" method="POST" class="space-y-4">
                    <input type="hidden" name="id_pangan" value="<?= $ep['id'] ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Nama Komoditas</label>
                        <input type="text" name="nama_komoditas" value="<?= htmlspecialchars($ep['nama_komoditas']) ?>" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Harga Per Satuan (Rp)</label>
                        <input type="number" name="harga" value="<?= $ep['harga'] ?>" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Stok (Angka)</label>
                        <input type="number" name="stok" value="<?= $ep['stok'] ?>" required class="w-full mt-1 p-2.5 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-200">Status Ketersediaan</label>
                        <select name="status" class="w-full mt-1 p-2.5 bg-gray-800 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                            <option value="Aman" <?= $ep['status'] == 'Aman' ? 'selected' : '' ?>>Aman</option>
                            <option value="Waspada" <?= $ep['status'] == 'Waspada' ? 'selected' : '' ?>>Waspada</option>
                            <option value="Langka" <?= $ep['status'] == 'Langka' ? 'selected' : '' ?>>Langka</option>
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" name="update_pangan" class="w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-2.5 rounded-xl font-bold transition cursor-pointer">Simpan Perubahan</button>
                        <a href="dashboard.php?tab=dashboard" class="w-full bg-white/10 text-center text-white p-2.5 rounded-xl font-bold transition pt-3">Batal</a>
                    </div>
                </form>
            </div>
        <?php 
            else:
                echo "<div class='bg-red-500/20 border border-red-500/30 text-red-200 p-3 rounded-xl'>Error 404: Data tidak ditemukan.</div>";
            endif;
        endif; 
        ?>

        <?php if ($tab == 'manajemen' && $role == 'admin'): ?>
            <div class="bg-white/5 backdrop-blur-md border border-white/10 p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-bold text-white mb-4">👥 Manajemen Akun Sistem</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 text-gray-400 text-sm uppercase">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Role</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-gray-200">
                        <?php
                        $users_query = $conn->query("SELECT * FROM users ORDER BY role ASC");
                        while($u = $users_query->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-3 font-semibold text-white"><?= htmlspecialchars($u['nama']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="p-3"><span class="bg-white/10 px-2 py-0.5 rounded-md text-xs uppercase font-bold text-green-300 border border-white/5"><?= $u['role'] ?></span></td>
                            <td class="p-3">
                                <a href="dashboard.php?hapus_user=<?= $u['id'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?')" class="text-red-400 hover:text-red-300 text-sm font-bold underline transition">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>