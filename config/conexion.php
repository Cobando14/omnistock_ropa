<?php
// Configuración de conexión a la base de datos OmniStock con pool de conexiones
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host = "localhost";
        $user = "root";
        $password = "obando123";
        $db = "omnistock_ropa";
        $port = 3306;

        // Usar conexión persistente para pool
        $this->conn = new mysqli('p:' . $host, $user, $password, $db, $port);

        // Verificar conexión
        if ($this->conn->connect_error) {
            $error_msg = "Error de conexión a la base de datos: " . htmlspecialchars($this->conn->connect_error);
            error_log("[OmniStock] " . date('Y-m-d H:i:s') . " - " . $error_msg);

            if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1:80' || $_SERVER['HTTP_HOST'] === 'localhost:80') {
                die("<h1>Error de Conexión</h1><p>$error_msg</p><p>Verifica que MySQL esté ejecutándose en XAMPP y que las credenciales sean correctas.</p><p><a href='../diagnostico.php'>Ver diagnóstico completo</a></p>");
            } else {
                die("No se pudo conectar a la base de datos. Por favor, contacta con el administrador.");
            }
        }

        // Configurar charset a UTF-8
        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
        self::$instance = null;
    }

    public function __destruct() {
        $this->close();
    }
}

// Para compatibilidad con código existente, crear una variable $conn
$db = Database::getInstance();
$conn = $db->getConnection();

// Función para cerrar la conexión manualmente si es necesario
function closeConnection() {
    global $db;
    if ($db) {
        $db->close();
    }
}

// Registrar un shutdown function para asegurar que la conexión se cierre automáticamente al final del script
register_shutdown_function('closeConnection');
?>
