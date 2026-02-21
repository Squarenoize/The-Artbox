<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Artwork.php';

class ArtworkModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll() {
        try {
            $stmt = $this->db->query
            ("SELECT * FROM artworks");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching artworks: " . $e->getMessage();
            return [];
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare
            ("SELECT * FROM artworks WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching artwork by ID: " . $e->getMessage();
            return null;
        }
    }

    public function create(Artwork $artwork) {
        try {
            $stmt = $this->db->prepare
            ("INSERT INTO artworks (title, artist, photo, description) 
            VALUES (:title, :artist, :photo, :description)");
            $stmt->bindParam(':title', $artwork->getTitle());
            $stmt->bindParam(':artist', $artwork->getArtist());
            $stmt->bindParam(':photo', $artwork->getPhoto());
            $stmt->bindParam(':description', $artwork->getDescription());
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error creating artwork: " . $e->getMessage();
            return false;
        }
    }
}