<?php
function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $key = trim($key);
        $value = trim($value);

        if ($key === '') {
            continue;
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

loadEnv(__DIR__ . '/.env');

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: '';
$db   = getenv('DB_DATABASE') ?: getenv('DB_NAME') ?: 'pantaupangan_plus';

// PDO Connection (Compatible dengan Railway, Vercel, XAMPP)
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Helper function untuk backward compatibility dengan mysqli
class DatabaseHelper {
    public static function query($conn, $sql) {
        try {
            return $conn->query($sql);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function num_rows($result) {
        return $result ? $result->rowCount() : 0;
    }

    public static function fetch_assoc($result) {
        return $result ? $result->fetch(PDO::FETCH_ASSOC) : null;
    }

    public static function real_escape_string($conn, $str) {
        return $str; // PDO dengan prepared statements tidak perlu escape
    }

    public static function insert_id($conn) {
        return $conn->lastInsertId();
    }
}

// Backward compatibility aliases
function mysqli_query($conn, $sql) {
    return DatabaseHelper::query($conn, $sql);
}

function mysqli_num_rows($result) {
    return DatabaseHelper::num_rows($result);
}

function mysqli_fetch_assoc($result) {
    return DatabaseHelper::fetch_assoc($result);
}

function mysqli_real_escape_string($conn, $str) {
    return DatabaseHelper::real_escape_string($conn, $str);
}

function mysqli_insert_id($conn) {
    return DatabaseHelper::insert_id($conn);
}
?>