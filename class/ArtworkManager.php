<?php
require_once __DIR__ . '/ArtworkDbTable.php';
require_once __DIR__ . '/Verification.php';

class ArtworkManager {
    private $artworkDbTable;
    private $verification;

    public function __construct() {
        $this->artworkDbTable = new ArtworkDbTable();
        $this->verification = new Verification();
    }

    public function fetchAll() {
        return $this->artworkDbTable->findAll();
    }

    public function getArtwork($id) {
        return $this->artworkDbTable->findById($id);
    }

    public function create(array $data, ?array $photo) {
       
        $title = $this->verification->verifyText($data['title'], 100);
        $artist = $this->verification->verifyText($data['artist'], 100);
        $description = $this->verification->verifyText($data['description'], 1000);

        if (!$title || !$artist || !$description) {
            return ['success' => false, 'message' => "Données textuelles invalides."];
        }

        if (!$photo || !$this->verification->verifyImage($photo)) {
            return ['success' => false, 'message' => "Fichier image invalide."];
        }
        
        $photoPath = $this->uploadNewPhoto($photo);

        if (!$photoPath) {
            return ['success' => false, 'message' => "Erreur lors de l'upload de l'image."];
        }

        // Create artwork
        $artwork = new Artwork([
            'work_title' => $title,
            'work_artist' => $artist,
            'work_photo_path' => $photoPath,
            'work_desc' => $description
        ]);

        if ($this->artworkDbTable->create($artwork)) {
            return ['success' => true, 'message' => "Oeuvre créée avec succès !"];
        } else {
            return ['success' => false, 'message' => "Erreur lors de la création de l'oeuvre."];
        }
    }

    public function update(array $data, ?array $photo) {

        if (!$data['id']) {
            return ['success' => false, 'message' => "ID de l'oeuvre invalide."];
        }

        
        // Retrieve the existing artwork to compare changes and handle photo replacement if needed
        $existingArtwork = $this->artworkDbTable->findById($data['id']);
        if (!$existingArtwork) {
            return ['success' => false, 'message' => "Oeuvre introuvable."];
        }

        // Vérifier les données textuelles
        $title = $this->verification->verifyText($data['title'], 100);
        $artist = $this->verification->verifyText($data['artist'], 100);
        $description = $this->verification->verifyText($data['description'], 1000);
        
        if ($title === false || $artist === false || $description === false) {
            return ['success' => false, 'message' => "Données invalides."];
        }

        // Handling photo: if a new file is uploaded, validate it
        $photoPath = $existingArtwork->getPhoto();
        if ($photo && $photo['size'] > 0) {
            if ($this->verification->verifyImage($photo)) {
                $photoPath = $this->uploadNewPhoto($photo);
                if (!$photoPath) {
                    return ['success' => false, 'message' => "Erreur lors de l'upload de la nouvelle image."];
                }
                $this->deletePhoto($existingArtwork->getPhoto());
            } else {
                return ['success' => false, 'message' => "Fichier image invalide."];
            }
        }

        // Create an array with the mandatory ID and the modified fields
        $dataToUpdate = ['work_id' => $data['id']];
        
        if ($title !== $existingArtwork->getTitle()) {
            $dataToUpdate['work_title'] = $title;
        }

        if ($artist !== $existingArtwork->getArtist()) {
            $dataToUpdate['work_artist'] = $artist;
        }

        if ($photoPath !== $existingArtwork->getPhoto()) {
            $dataToUpdate['work_photo_path'] = $photoPath;
        }

        if ($description !== $existingArtwork->getDescription()) {
            $dataToUpdate['work_desc'] = $description;
        }

        // If only the ID is present, no modification
        if (count($dataToUpdate) === 1) {
            return ['success' => false, 'message' => "Aucune modification détectée.", 'type' => 'info'];
        }

        $updatedArtwork = new Artwork($dataToUpdate);

        if ($this->artworkDbTable->update($updatedArtwork)) {
            return ['success' => true, 'message' => "Oeuvre mise à jour avec succès !"];
        } else {
            return ['success' => false, 'message' => "Erreur lors de la mise à jour de l'oeuvre."];
        }
    }

    public function delete($id) {
        if (!isset($id) || $id === '' || !ctype_digit((string)$id)) {
            return ['success' => false, 'message' => "ID de l'oeuvre invalide."];
        }

        // Retrieve the existing artwork
        $existingArtwork = $this->artworkDbTable->findById($id);
        if (!$existingArtwork) {
            return ['success' => false, 'message' => "Oeuvre introuvable."];
        }

        if ($this->artworkDbTable->delete($id)) {
            // Delete the associated photo
            $this->deletePhoto($existingArtwork->getPhoto());
            return ['success' => true, 'message' => "Oeuvre supprimée avec succès !"];
        } else {
            return ['success' => false, 'message' => "Erreur lors de la suppression de l'oeuvre."];
        }
    }

    private function uploadNewPhoto($photo) {
        $targetDir = __DIR__ . '/../img/';
        $targetFile = $targetDir . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $targetFile)) {
            return 'img/' . basename($photo['name']);
        } else {
            return null;
        }
    }

    private function deletePhoto($photoPath) {
        $fullPath = __DIR__ . '/../' . $photoPath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
} 