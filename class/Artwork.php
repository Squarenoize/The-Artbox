<?php
class Artwork {
    private $id;
    private $title;
    private $artist;
    private $photo;
    private $description;
    

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->artist = $data['artist'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->description = $data['description'] ?? null;
        
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setArtist($artist) {
        $this->artist = $artist;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getArtist() {
        return $this->artist;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPhoto() {
        return $this->photo;
    }
}