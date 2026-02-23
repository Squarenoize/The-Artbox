<?php
require_once __DIR__ . '/ArtworkModel.php';
require_once __DIR__ . '/Verification.php';

class ArtworkController {
    private $model;
    private $verifier;

    public function __construct() {
        $this->model = new ArtworkModel();
        $this->verifier = new Verification();
    }

    public function fetchAll() {
        return $this->model->findAll();
    }

    public function getArtwork($id) {
        return $this->model->findById($id);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $artist = $_POST['artist'] ?? '';
            $photo = $_FILES['photo'] ?? null;
            $description = $_POST['description'] ?? '';
            

            if ($this->verifier->verifyText($title) && 
                $this->verifier->verifyText($artist) && 
                $this->verifier->verifyText($description) && 
                $this->verifier->verifyImage($photo)) {

                // Handle file upload and create artwork
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
            $existingArtwork = $this->model->findById($id);
            if (!$existingArtwork) {
                echo "Œuvre introuvable.";
                return false;
            }

            // Vérifier les données textuelles
            if ($this->verifier->verifyText($title) && 
                $this->verifier->verifyText($artist) && 
                $this->verifier->verifyText($description)) {

                // Gérer la photo : si un nouveau fichier est uploadé, le valider
                $photoPath = $existingArtwork->getPhoto();
                if ($photo && $photo['size'] > 0) {
                    if ($this->verifier->verifyImage($photo)) {
                        // Upload de la nouvelle photo
                        // Pour l'instant, on garde l'ancienne logique
                        // $photoPath = uploadNewPhoto($photo);
                    } else {
                        echo "Fichier image invalide.";
                        return false;
                    }
                }

                // Créer un objet Artwork mis à jour
                $updatedArtwork = new Artwork([
                    'work_id' => $id,
                    'title' => $title,
                    'artist' => $artist,
                    'photo' => $photoPath,
                    'description' => $description
                ]);

                if ($this->model->update($updatedArtwork)) {
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
} 