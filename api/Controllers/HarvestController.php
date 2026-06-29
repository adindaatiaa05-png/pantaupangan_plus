<?php

class HarvestController
{
    private $db;

    // Kunci penghubung ke database config.php milikmu
    public function __construct()
    {
        include __DIR__ . '/../../config.php';
        $this->db = $conn;
    }

    /**
     * 1. GET ALL
     */
    public function index()
    {
        header('Content-Type: application/json');
        try {
            $result = $this->db->query("SELECT * FROM pangan");
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['data' => $data]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mengambil data']);
        }
    }

    /**
     * 2. POST (Create)
     */
    public function store()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $_POST;
        }

        $komoditas = $input['nama_komoditas'] ?? '';
        $harga = intval($input['harga'] ?? 0);
        $stok = intval($input['stok'] ?? 0);
        $status = $input['status'] ?? 'Aman';

        try {
            $stmt = $this->db->prepare("INSERT INTO pangan (nama_komoditas, harga, stok, status, diupdate_oleh) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$komoditas, $harga, $stok, $status, 'API System'])) {
                http_response_code(201);
                echo json_encode(['message' => 'Data hasil panen berhasil dicatat']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal mencatat data pangan']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mencatat data pangan']);
        }
    }

    /**
     * 3. GET BY ID
     */
    public function show($id)
    {
        header('Content-Type: application/json');
        $id = intval($id);
        try {
            $stmt = $this->db->prepare("SELECT * FROM pangan WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                echo json_encode(['data' => $result[0]]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Data panen dengan ID $id tidak ada di sistem."]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan']);
        }
    }

    /**
     * 4. PUT (Update - TUGAS MANDIRI)
     */
    public function update($id)
    {
        header('Content-Type: application/json');
        $id = intval($id);
        
        // Membaca raw input body (x-www-form-urlencoded atau JSON dari Postman)
        parse_str(file_get_contents("php://input"), $input);
        if (!$input) {
            $input = json_decode(file_get_contents('php://input'), true);
        }

        try {
            $cek = $this->db->prepare("SELECT id FROM pangan WHERE id = ?");
            $cek->execute([$id]);
            if ($cek->rowCount() > 0) {
                $komoditas = $input['nama_komoditas'] ?? '';
                $harga = intval($input['harga'] ?? 0);
                $stok = intval($input['stok'] ?? 0);
                $status = $input['status'] ?? 'Aman';

                $stmt = $this->db->prepare("UPDATE pangan SET nama_komoditas=?, harga=?, stok=?, status=?, diupdate_oleh=? WHERE id=?");
                $stmt->execute([$komoditas, $harga, $stok, $status, 'API System', $id]);
                echo json_encode(['message' => 'Data hasil panen berhasil diperbarui']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Gagal memperbarui! Data panen dengan ID $id tidak ditemukan."]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan']);
        }
    }

    /**
     * 5. DELETE (Destroy - TUGAS MANDIRI)
     */
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $id = intval($id);

        try {
            $cek = $this->db->prepare("SELECT id FROM pangan WHERE id = ?");
            $cek->execute([$id]);
            if ($cek->rowCount() > 0) {
                $stmt = $this->db->prepare("DELETE FROM pangan WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['status' => 'Sukses', 'message' => "Data panen dengan ID $id telah berhasil dihapus dari sistem."]);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Gagal menghapus! Data panen dengan ID $id tidak terdaftar."]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan']);
        }
    }
}