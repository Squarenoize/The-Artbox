<?php
class Artwork {
    private $id;
    private $title;
    private $artist;
    private $photo;
    private $description;
    

    public function __construct(array $data) {
        $this->id = $data['work_id'] ?? null;

        if (isset($data['work_title'])) {
            $this->title = $data['work_title'];
        }
        if (isset($data['work_artist'])) {
            $this->artist = $data['work_artist'];
        }
        if (isset($data['work_desc'])) {
            $this->description = $data['work_desc'];
        }
        if (isset($data['work_photo_path'])) {
            $this->photo = $data['work_photo_path'];
        }
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