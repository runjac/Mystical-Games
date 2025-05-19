<?php
class Cart {
    private $db;
    private $table = 'games';

    public function __construct($db) {
        $this->db = $db;
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    public function add($gameId, $quantity) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE game_id = :id");
        $stmt->execute(['id' => $gameId]);
        $game = $stmt->fetch();

        if (!$game) {
            return ['status' => false, 'error' => 'Juego no encontrado'];
        }

        $cart = $_SESSION['carrito'];
        $exists = false;
        $index = null;

        foreach ($cart as $key => $item) {
            if ($item['id'] == $gameId) {
                $exists = true;
                $index = $key;
                break;
            }
        }

        if ($exists) {
            $newQuantity = $cart[$index]['cantidad'] + $quantity;
            if ($game['stock'] >= $newQuantity) {
                $cart[$index]['cantidad'] = $newQuantity;
                $_SESSION['carrito'] = $cart;
                return ['status' => true];
            }
            return ['status' => false, 'error' => 'Stock insuficiente'];
        }

        if ($game['stock'] >= $quantity) {
            $cart[] = [
                'id' => $game['game_id'],
                'titulo' => $game['titulo'],
                'img' => $game['img'],
                'precio' => $game['precio'],
                'cantidad' => $quantity
            ];
            $_SESSION['carrito'] = $cart;
            return ['status' => true];
        }

        return ['status' => false, 'error' => 'Stock insuficiente'];
    }

    public function update($gameId, $quantity) {
        $stmt = $this->db->prepare("SELECT stock FROM {$this->table} WHERE game_id = :id");
        $stmt->execute(['id' => $gameId]);
        $game = $stmt->fetch();

        if ($game['stock'] >= $quantity) {
            $cart = $_SESSION['carrito'];
            foreach ($cart as &$item) {
                if ($item['id'] == $gameId) {
                    $item['cantidad'] = $quantity;
                    break;
                }
            }
            $_SESSION['carrito'] = $cart;
            return ['status' => true];
        }

        return ['status' => false, 'error' => 'Stock insuficiente'];
    }

    public function remove($gameId) {
        $cart = $_SESSION['carrito'];
        foreach ($cart as $key => $item) {
            if ($item['id'] == $gameId) {
                unset($cart[$key]);
                break;
            }
        }
        $_SESSION['carrito'] = array_values($cart);
        return ['status' => true];
    }

    public function getCart() {
        return $_SESSION['carrito'];
    }

    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
}