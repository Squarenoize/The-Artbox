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
} 