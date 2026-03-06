<?php
require_once __DIR__ . '/ArtworkManager.php';

class ArtworkDataHelper {
    private $artworkManager;

    public function __construct() {
        $this->artworkManager = new ArtworkManager();
    }

    /**
     * Displays a message to the user
     */
    private function displayMessage($result) {
        if (isset($result['message'])) {
            $type = $result['type'] ?? ($result['success'] ? 'success' : 'error');
            echo "<div class='dashboard-message {$type}'>{$result['message']}</div>";
        }
    }

    /**
     * Handles all dashboard actions
     * This is the main method called by the view
     */
    public function handleAction($action) {
        switch ($action) {
            case 'add_artwork':
                $this->handleAddArtwork();
                break;
            case 'update_artwork':
                $this->handleUpdateArtwork();
                break;
            case 'delete_artwork':
                $this->handleDeleteArtwork();
                break;
            default:
                $this->handleDefault();
                break;
        }
    }

    /**
     * Handles adding a new artwork
     */
    private function handleAddArtwork() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'title' => $_POST['title'] ?? '',
                'artist' => $_POST['artist'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            $photo = $_FILES['photo'] ?? null;

            $result = $this->artworkManager->create($data, $photo);

            $this->displayMessage($result);
        }
        include_once __DIR__ . '/../includes/form_artwork.php';
    }

    /**
     * Handles updating artworks
     */
    private function handleUpdateArtworks() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processUpdate();
            return;
        }

        $this->showUpdatePage();
    }

    private function processUpdate() {
        $data = [
            'id' => $_POST['id'] ?? null,
            'title' => $_POST['title'] ?? '',
            'artist' => $_POST['artist'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        $photo = $_FILES['photo'] ?? null;

        $result = $this->artworkManager->update($data, $photo);
        $this->displayMessage($result);
    }

    private function showUpdatePage() {
        $artworkId = $_GET['id'] ?? null;

        if (!ctype_digit($artworkId)) {
            die("ID invalide");
        }

        if (!$artworkId) {
            $artworks = $this->artworkManager->fetchAll();
            include '../includes/artwork_list.php';
            return;
        }

        $artwork = $this->artworkManager->getArtwork($artworkId);

        if (!$artwork) {
            echo "<div class='dashboard-message error'>Oeuvre introuvable.</div>";
            return;
        }

        include '../includes/form_artwork.php';
    }

    /**
     * Handle deleting an artwork
     */
    private function handleDeleteArtwork() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            if (!$id) {
                echo "<div class='dashboard-message error'>ID de l'oeuvre invalide.</div>";
                return;
            }

            $result = $this->artworkManager->delete($id);

            if ($result['success']) {
                header("Location: dashboard.php?action=delete_artwork");
                exit;
            } else {
                $this->displayMessage($result);
            }
        }

        $artworkId = $_GET['id'] ?? null;
        
        if (!$artworkId) {
            // Prepare data for the view
            $action = $_GET['action'];
            $artworks = $this->artworkManager->fetchAll();
            include_once __DIR__ . '/../includes/artwork_list.php';
        } else {
            // Fetch the artwork and show confirmation
            $artwork = $this->artworkManager->getArtwork($artworkId);
            if (!$artwork) {
                echo "<div class='dashboard-message error'>Oeuvre introuvable.</div>";
                $action = $_GET['action'];
                $artworks = $this->artworkManager->fetchAll();
                include_once __DIR__ . '/../includes/artwork_list.php';
            } else {
                include_once __DIR__ . '/../includes/confirm_delete.php';
            }
        }
    }

    /**
     * Handles the default case (no action)
     */
    private function handleDefault() {
        echo "<div class='dashboard-message info'>Veuillez sélectionner une action dans le menu ci-dessus pour gérer vos oeuvres d'art.</div>";
    }

    /**
     * Fetches all artworks (for oeuvre.php and index.php)
     */
    public function fetchAll() {
        return $this->artworkManager->fetchAll();
    }

    /**
     * Fetches an artwork by ID (for oeuvre.php)
     */
    public function getArtwork($id) {
        return $this->artworkManager->getArtwork($id);
    }
}
