<?php

class HarvestController
{
    private $db;

    // Kunci penghubung ke database config.php milikmu
    public function __construct()
    {
        include __DIR__ . '/../config.php';
        $this->db = $conn;
    }

    /**
     * 1. GET ALL
     */
    public function index()
    {
        header('Content-Type: application_json');
        $query = "SELECT * FROM pangan";
        $result = mysqli_query($this->db, $query);
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        echo json_encode(['data' => $data]);
    }

    /**
     * 2. POST (Create)
     */
    public function store()
    {
        header('Content-Type: application_json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            $input = $_POST;
        }

        $komoditas = mysqli_real_escape_string($this->db, $input['nama_komoditas'] ?? '');
        $harga = intval($input['harga'] ?? 0);
        $stok = intval($input['stok'] ?? 0);
        $status = mysqli_real_escape_string($this->db, $input['status'] ?? 'Aman');

        $query = "INSERT INTO pangan (nama_komoditas, harga, stok, status, diupdate_oleh) VALUES ('$komoditas', $harga, $stok, '$status', 'API System')";
        
        if (mysqli_query($this->db, $query)) {
            http_response_code(201);
            echo json_encode(['message' => 'Data hasil panen berhasil dicatat']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal mencatat data pangan']);
        }
    }

    /**
     * 3. GET BY ID
     */
    public function show($id)
    {
        header('Content-Type: application_json');
        $id = intval($id);
        $query = "SELECT * FROM pangan WHERE id = $id";
        $result = mysqli_query($this->db, $query);

        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['data' => mysqli_fetch_assoc($result)]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Data panen dengan ID $id tidak ada di sistem."]);
        }
    }

    /**
     * 4. PUT (Update - TUGAS MANDIRI)
     */
    public function update($id)
    {
        header('Content-Type: application_json');
        $id = intval($id);
        
        // Membaca raw input body (x-www-form-urlencoded atau JSON dari Postman)
        parse_str(file_get_contents("php://input"), $input);
        if (!$input) {
            $input = json_decode(file_get_contents('php://input'), true);
        }

        $cek = mysqli_query($this->db, "SELECT id FROM pangan WHERE id = $id");
        if (mysqli_num_rows($cek) > 0) {
            $komoditas = mysqli_real_escape_string($this->db, $input['nama_komoditas'] ?? '');
            $harga = intval($input['harga'] ?? 0);
            $stok = intval($input['stok'] ?? 0);
            $status = mysqli_real_escape_string($this->db, $input['status'] ?? 'Aman');

            mysqli_query($this->db, "UPDATE pangan SET nama_komoditas='$komoditas', harga=$harga, stok=$stok, status='$status', diupdate_oleh='API System' WHERE id=$id");
            echo json_encode(['message' => 'Data hasil panen berhasil diperbarui']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Gagal memperbarui! Data panen dengan ID $id tidak ditemukan."]);
        }
    }

    /**
     * 5. DELETE (Destroy - TUGAS MANDIRI)
     */
    public function destroy($id)
    {
        header('Content-Type: application_json');
        $id = intval($id);

        $cek = mysqli_query($this->db, "SELECT id FROM pangan WHERE id = $id");
        if (mysqli_num_rows($cek) > 0) {
            mysqli_query($this->db, "DELETE FROM pangan WHERE id = $id");
            echo json_encode(['status' => 'Sukses', 'message' => "Data panen dengan ID $id telah berhasil dihapus dari sistem."]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Resource tidak ditemukan', 'message' => "Gagal menghapus! Data panen dengan ID $id tidak terdaftar."]);
        }
    }
}