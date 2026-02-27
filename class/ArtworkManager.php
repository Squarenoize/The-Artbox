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

    public function create() {
        $title = $_POST['title'] ?? '';
        $artist = $_POST['artist'] ?? '';
        $photo = $_FILES['photo'] ?? null;
        $description = $_POST['description'] ?? '';
        
        $title = $this->verification->verifyText($title, 100);
        $artist = $this->verification->verifyText($artist, 100);
        $description = $this->verification->verifyText($description, 1000);
        
        if ($title && $artist && $description && $this->verification->verifyImage($photo)) {
            // Sauvegarder l'image
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
        } else {
            return ['success' => false, 'message' => "Données invalides."];
        }
    }

    public function update() {
        $id = $_POST['id'] ?? null;
        $title = $_POST['title'] ?? '';
        $artist = $_POST['artist'] ?? '';
        $photo = $_FILES['photo'] ?? null;
        $description = $_POST['description'] ?? '';

        if (!$id) {
            return ['success' => false, 'message' => "ID de l'oeuvre invalide."];
        }

        // Récupérer l'oeuvre existante
        $existingArtwork = $this->artworkDbTable->findById($id);
        if (!$existingArtwork) {
            return ['success' => false, 'message' => "Oeuvre introuvable."];
        }

        // Vérifier les données textuelles
        $title = $this->verification->verifyText($title, 100);
        $artist = $this->verification->verifyText($artist, 100);
        $description = $this->verification->verifyText($description, 1000);
        
        if ($title && $artist && $description) {

            // Gérer la photo : si un nouveau fichier est uploadé, le valider
            $photoPath = $existingArtwork->getPhoto();
            if ($photo && $photo['size'] > 0) {
                if ($this->verification->verifyImage($photo)) {
                    // Upload de la nouvelle photo
                    $photoPath = $this->uploadNewPhoto($photo);
                    if (!$photoPath) {
                        return ['success' => false, 'message' => "Erreur lors de l'upload de la nouvelle image."];
                    }
                    // Supprimer l'ancienne photo
                    $this->deletePhoto($existingArtwork->getPhoto());
                } else {
                    return ['success' => false, 'message' => "Fichier image invalide."];
                }
            }

            // Créer un tableau avec l'ID obligatoire et les champs modifiés
            $dataToUpdate = ['work_id' => $id];
            
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

            // Si seul l'ID est présent, aucune modification
            if (count($dataToUpdate) === 1) {
                return ['success' => false, 'message' => "Aucune modification détectée.", 'type' => 'info'];
            }

            $updatedArtwork = new Artwork($dataToUpdate);

            if ($this->artworkDbTable->update($updatedArtwork)) {
                return ['success' => true, 'message' => "Oeuvre mise à jour avec succès !"];
            } else {
                return ['success' => false, 'message' => "Erreur lors de la mise à jour de l'oeuvre."];
            }
        } else {
            return ['success' => false, 'message' => "Données invalides."];
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return ['success' => false, 'message' => "ID de l'oeuvre invalide."];
        }

        // Récupérer l'oeuvre existante
        $existingArtwork = $this->artworkDbTable->findById($id);
        if (!$existingArtwork) {
            return ['success' => false, 'message' => "Oeuvre introuvable."];
        }

        if ($this->artworkDbTable->delete($id)) {
            // Supprimer la photo associée
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