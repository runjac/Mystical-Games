<?php
class User {
    private $db;
    private $table = 'usuarios';

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['usuario'] = $user['usuario'];
            return true;
        }
        return false;
    }

    public function register($username, $email, $password, $dateOfBirth, $photoName) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (usuario, email, password, fecha_nacimiento, foto) VALUES (:username, :email, :password, :date_of_birth, :foto)");
            
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':date_of_birth' => $dateOfBirth,
                ':foto' => $photoName
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}