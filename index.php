
    <?php
    require_once __DIR__ . '/class/ArtworkDataHelper.php';
    include_once 'includes/header.php'; ?>
        <div id="liste-oeuvres">
            <?php
            $dataHelper = new ArtworkDataHelper();
            $artworks = $dataHelper->fetchAll();
            foreach ($artworks as $artwork) {
                ?>
                <article class="oeuvre">
                    <a href="oeuvre.php?id=<?= htmlspecialchars($artwork->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?= htmlspecialchars($artwork->getPhoto(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?>">
                        <h2><?= htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h2>
                        <p class="description"><?= htmlspecialchars($artwork->getArtist(), ENT_QUOTES, 'UTF-8'); ?></p>
                    </a>
                </article>
            <?php
            }
            ?>
        </div>
    <?php 
    include_once 'includes/footer.php';
    
