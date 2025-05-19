<?php
class Order {
    private $db;
    private $table = 'ventas';

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($orderData, $cart) {
        try {
            $this->db->beginTransaction();

            // Insert main order
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table} 
                (nombre, direccion, pais, tarjeta, titular, caducidad, cvv, usuario_id, total, fecha) 
                VALUES 
                (:nombre, :direccion, :pais, :tarjeta, :titular, :caducidad, :cvv, :usuario_id, :total, :fecha)
            ");

            $stmt->execute([
                'nombre' => $orderData['nombre'],
                'direccion' => $orderData['direccion'],
                'pais' => $orderData['pais'],
                'tarjeta' => $orderData['tarjeta'],
                'titular' => $orderData['titular'],
                'caducidad' => $orderData['caducidad'],
                'cvv' => $orderData['cvv'],
                'usuario_id' => $_SESSION['id_usuario'],
                'total' => $this->calculateTotal($cart),
                'fecha' => date('Y-m-d H:i:s')
            ]);

            $orderId = $this->db->lastInsertId();

            // Insert order details and update stock
            foreach ($cart as $item) {
                $this->insertOrderDetail($orderId, $item);
                $this->updateStock($item['id'], $item['cantidad']);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    private function calculateTotal($cart) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    private function insertOrderDetail($orderId, $item) {
        $stmt = $this->db->prepare("
            INSERT INTO venta_detalles 
            (venta_id, producto_id, precio, cantidad) 
            VALUES 
            (:venta_id, :producto_id, :precio, :cantidad)
        ");

        $stmt->execute([
            'venta_id' => $orderId,
            'producto_id' => $item['id'],
            'precio' => $item['precio'],
            'cantidad' => $item['cantidad']
        ]);
    }

    private function updateStock($productId, $quantity) {
        $stmt = $this->db->prepare("
            UPDATE games 
            SET stock = stock - :cantidad 
            WHERE game_id = :id
        ");

        $stmt->execute([
            'cantidad' => $quantity,
            'id' => $productId
        ]);
    }
}