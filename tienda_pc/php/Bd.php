<?php
/** Ubicación: php/Bd.php */
class Bd {
  private static ?PDO $pdo = null;

  public static function pdo(): PDO {
    if (self::$pdo === null) {
      self::$pdo = new PDO("sqlite:" . __DIR__ . "/srvcompras.db");
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::prepara();
    }
    return self::$pdo;
  }

  private static function prepara() {
    self::$pdo->exec("CREATE TABLE IF NOT EXISTS PRODUCTO (
      PROD_ID INTEGER PRIMARY KEY AUTOINCREMENT,
      PROD_NOMBRE TEXT NOT NULL,
      PROD_EXISTENCIAS INTEGER NOT NULL,
      PROD_PRECIO REAL NOT NULL
    )");

    $stmt = self::$pdo->query("SELECT COUNT(*) FROM PRODUCTO");
    if ($stmt->fetchColumn() == 0) {
      self::$pdo->exec("INSERT INTO PRODUCTO (PROD_NOMBRE, PROD_EXISTENCIAS, PROD_PRECIO) VALUES
        ('Tarjeta NVIDIA RTX 4060 Ti', 12, 8950.00),
        ('Procesador AMD Ryzen 7 5800X', 8, 4300.00),
        ('RAM 16GB DDR4 Corsair', 25, 1200.00),
        ('SSD NVMe 1TB Kingston', 15, 1380.00),
        ('Fuente 750W 80+ Gold', 5, 2200.00),
        ('Gabinete Gamer RGB ATX', 10, 1850.00)");
    }
  }
}