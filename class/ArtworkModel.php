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
            ("SELECT * FROM works");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $artworks = [];
            foreach ($result as $data) {
                $artworks[] = new Artwork($data);
            }
            return $artworks;

        } catch (PDOException $e) {
            echo "Error fetching artworks: " . $e->getMessage();
            return [];
        }
    }

    public function findById($id) {
        try {
            $stmt = $this->db->prepare
            ("SELECT * FROM works WHERE work_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return new Artwork($data);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Error fetching artwork by ID: " . $e->getMessage();
            return null;
        }
    }

    public function create(Artwork $artwork) {
        try {
            $stmt = $this->db->prepare
            ("INSERT INTO works (title, artist, photo, description) 
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