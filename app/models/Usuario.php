<?php
require_once 'Model.php';

class Usuario extends Model {
    public function __construct() {
        parent::__construct('usuarios');
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ? AND estado = 'activo'");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function createUser($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        return $this->create($data);
    }
}
?>