<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Artwork.php';

class ArtworkDbTable {
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
            error_log("Error fetching artworks: " . $e->getMessage());
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
            error_log("Error fetching artwork by ID: " . $e->getMessage());
            return null;
        }
    }

    public function create(Artwork $artwork) {
        try {
            $stmt = $this->db->prepare
            ("INSERT INTO works (work_title, work_artist, work_photo_path, work_desc) 
            VALUES (:title, :artist, :photo, :description)");
            $stmt->bindValue(':title', $artwork->getTitle());
            $stmt->bindValue(':artist', $artwork->getArtist());
            $stmt->bindValue(':photo', $artwork->getPhoto());
            $stmt->bindValue(':description', $artwork->getDescription());
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating artwork: " . $e->getMessage());
            return false;
        }
    }

    public function update(Artwork $artwork) {
        try {
            // Construire dynamiquement la requête SQL
            $fields = [];
            $params = [':id' => $artwork->getId()];

            if ($artwork->getTitle() !== null) {
                $fields[] = "work_title = :title";
                $params[':title'] = $artwork->getTitle();
            }

            if ($artwork->getArtist() !== null) {
                $fields[] = "work_artist = :artist";
                $params[':artist'] = $artwork->getArtist();
            }

            if ($artwork->getPhoto() !== null) {
                $fields[] = "work_photo_path = :photo";
                $params[':photo'] = $artwork->getPhoto();
            }

            if ($artwork->getDescription() !== null) {
                $fields[] = "work_desc = :description";
                $params[':description'] = $artwork->getDescription();
            }

            // Si aucun champ à mettre à jour
            if (empty($fields)) {
                return true;
            }

            // Construire et exécuter la requête
            $sql = "UPDATE works SET " . implode(', ', $fields) . " WHERE work_id = :id";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating artwork: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare
            ("DELETE FROM works WHERE work_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting artwork: " . $e->getMessage());
            return false;
        }
    }
}