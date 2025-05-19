<?php
class Game {
    private $db;
    private $table = 'games';

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllGames() {
        $sql = "
        SELECT
            games.game_id,
            games.titulo,
            games.img,
            games.fecha_estreno,
            games.precio,
            consolas.nombres AS consola,
            GROUP_CONCAT(categorias.nombres SEPARATOR ', ') AS categorias
        FROM games
        INNER JOIN consolas ON games.console_id = consolas.consola_id
        INNER JOIN game_categorias ON games.game_id = game_categorias.game_id
        INNER JOIN categorias ON categorias.categorias_id = game_categorias.categorias_id
        GROUP BY games.game_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getGameById($id) {
        $sql = "
        SELECT
            games.*,
            consolas.nombres AS consola,
            GROUP_CONCAT(categorias.nombres SEPARATOR ', ') AS categorias
        FROM games
        INNER JOIN consolas ON games.console_id = consolas.consola_id
        INNER JOIN game_categorias ON games.game_id = game_categorias.game_id
        INNER JOIN categorias ON categorias.categorias_id = game_categorias.categorias_id
        WHERE games.game_id = :id
        GROUP BY games.game_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function searchGames($filters = []) {
        $sql = "
        SELECT
            games.game_id,
            games.titulo,
            games.img,
            games.fecha_estreno,
            games.precio,
            consolas.nombres AS consola,
            GROUP_CONCAT(categorias.nombres SEPARATOR ', ') AS categorias
        FROM games
        INNER JOIN consolas ON games.console_id = consolas.consola_id
        INNER JOIN game_categorias ON games.game_id = game_categorias.game_id
        INNER JOIN categorias ON categorias.categorias_id = game_categorias.categorias_id
        WHERE 1=1";

        $params = [];

        if (!empty($filters['console'])) {
            $sql .= " AND consolas.nombres = :console";
            $params[':console'] = $filters['console'];
        }

        if (!empty($filters['titulo'])) {
            $sql .= " AND games.titulo LIKE :titulo";
            $params[':titulo'] = "%{$filters['titulo']}%";
        }

        $sql .= " GROUP BY games.game_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}