<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Artwork.php';

class ArtworkDbTable {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieves all artwork records from the database.
     *
     * Fetches all rows from the 'works' table and converts each row into an Artwork object.
     *
     * @return Artwork[] An array of Artwork objects representing all artworks in the database.
     *                   Returns an empty array if an error occurs during the database query.
     *
     * @throws PDOException Caught internally and logged; does not propagate to caller.
     */
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

    /**
     * Retrieves an artwork record by its ID from the database.
     *
     * @param int $id The unique identifier of the artwork to retrieve.
     *
     * @return Artwork|null Returns an Artwork object if the artwork is found,
     *                      or null if the artwork does not exist or an error occurs.
     *
     * @throws void This method catches PDOException internally and logs errors
     *              without re-throwing exceptions.
     */
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

    /**
     * Creates a new artwork record in the database.
     *
     * Inserts a new artwork entry into the 'works' table with the provided
     * artwork information including title, artist, photo path, and description.
     *
     * @param Artwork $artwork The artwork object containing the data to be inserted.
     *                         Must have valid getTitle(), getArtist(), getPhoto(),
     *                         and getDescription() methods.
     *
     * @return bool True if the artwork was successfully created, false otherwise.
     *
     * @throws PDOException Caught internally and logged; does not propagate.
     */
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

    /**
     * Updates an existing artwork record in the database
     *
     * Dynamically builds and executes an UPDATE query based on which fields
     * of the Artwork object are not null. Only non-null fields are included
     * in the update to allow for partial updates.
     *
     * @param Artwork $artwork The artwork object containing the data to update.
     *                          The object's ID is used to identify the record to update.
     *                          Only non-null properties will be updated in the database.
     *
     * @return bool True if the update was successful or if no fields needed updating,
     *              false if the update failed or a database error occurred.
     *
     * @throws PDOException Caught internally and logged; returns false instead of throwing.
     */
    public function update(Artwork $artwork) {
        try {
            // Build dynamic SQL query based on non-null fields
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

            // If no fields to update
            if (empty($fields)) {
                return true;
            }

            // Build and execute the query
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

    /**
     * Deletes an artwork record from the database by its ID.
     *
     * @param int $id The ID of the artwork to delete
     * @return bool Returns true if the deletion was successful, false otherwise
     * @throws PDOException Caught and logged internally, returns false instead
     */
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