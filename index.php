
    <?php
    require_once __DIR__ . '/class/ArtworkController.php';
    include_once 'includes/header.php'; ?>
        <div id="liste-oeuvres">
            <?php
            $controller = new ArtworkController();
            $artworks = $controller->fetchAll();
            foreach ($artworks as $artwork) {
                ?>
                <article class="oeuvre">
                    <a href="oeuvre.php?id=<?= $artwork->getId(); ?>">
                        <img src="<?= $artwork->getPhoto(); ?>" alt="<?= $artwork->getTitle(); ?>">
                        <h2><?= $artwork->getTitle(); ?></h2>
                        <p class="description"><?= $artwork->getArtist(); ?></p>
                    </a>
                </article>
            <?php
            }
            ?>
        </div>
    <?php 
    include_once 'includes/footer.php';
    
