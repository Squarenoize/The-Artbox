<?php
require_once __DIR__ . '/ArtworkModel.php';
require_once __DIR__ . '/Verification.php';

class ArtworkController {
    private $artworkModel;
    private $verification;

    public function __construct() {
        $this->artworkModel = new ArtworkModel();
        $this->verification = new Verification();
    }

    public function fetchAll() {
        return $this->artworkModel->findAll();
    }

    public function getArtwork($id) {
        return $this->artworkModel->findById($id);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                    echo "Erreur lors de l'upload de l'image.";
                    return false;
                }

                // Create artwork
                $artwork = new Artwork([
                    'work_title' => $title,
                    'work_artist' => $artist,
                    'work_photo_path' => $photoPath,
                    'work_desc' => $description
                ]);

                if ($this->artworkModel->create($artwork)) {
                    echo "<div class='success-message'>Œuvre créée avec succès !</div>";
                    return true;
                } else {
                    echo "<div class='error-message'>Erreur lors de la création de l'œuvre.</div>";
                    return false;
                }
                // ...
            } else {
                echo "Invalid input data.";
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $title = $_POST['title'] ?? '';
            $artist = $_POST['artist'] ?? '';
            $photo = $_FILES['photo'] ?? null;
            $description = $_POST['description'] ?? '';

            if (!$id) {
                echo "Invalid artwork ID.";
                return false;
            }

            // Récupérer l'œuvre existante
            $existingArtwork = $this->artworkModel->findById($id);
            if (!$existingArtwork) {
                echo "Œuvre introuvable.";
                return false;
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
                            echo "Erreur lors de l'upload de la nouvelle image.";
                            return false;
                        }
                        // Supprimer l'ancienne photo
                        $this->deletePhoto($existingArtwork->getPhoto());
                    } else {
                        echo "Fichier image invalide.";
                        return false;
                    }
                }

                // Créer un objet Artwork mis à jour
                $updatedArtwork = new Artwork([
                    'work_id' => $id,
                    'work_title' => $title,
                    'work_artist' => $artist,
                    'work_photo_path' => $photoPath,
                    'work_desc' => $description
                ]);

                if ($this->artworkModel->update($updatedArtwork)) {
                    echo "<div class='success-message'>Œuvre mise à jour avec succès !</div>";
                    return true;
                } else {
                    echo "<div class='error-message'>Erreur lors de la mise à jour.</div>";
                    return false;
                }
            } else {
                echo "Données invalides.";
                return false;
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "Invalid artwork ID given.";
                return false;
            }

            // Récupérer l'œuvre existante
            $existingArtwork = $this->artworkModel->findById($id);
            if (!$existingArtwork) {
                echo "Œuvre introuvable.";
                return false;
            }

            if ($this->artworkModel->delete($id)) {
                // Supprimer la photo associée
                $this->deletePhoto($existingArtwork->getPhoto());
                echo "Œuvre supprimée avec succès !";
            } else {
                return false;
            }
        }
    }

    private function uploadNewPhoto($photo) {
        $targetDir = __DIR__ . '/../img/';
        $targetFile = $targetDir . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $targetFile)) {
            return 'img/' . basename($photo['name']);
        } else {
            echo "Erreur lors de l'upload de l'image.";
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