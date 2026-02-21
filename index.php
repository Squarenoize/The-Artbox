
    <?php
    include_once 'includes/header.php'; ?>
        <div id="liste-oeuvres">
            <?php 
            require_once 'oeuvres.php';
            foreach ($oeuvres as $oeuvre) {
                ?>
                <article class="oeuvre">
                        <a href="oeuvre.php?id=<?= $oeuvre['id']; ?>">
                            <img src="<?= $oeuvre['img']; ?>" alt="<?= $oeuvre['title']; ?>">
                            <h2><?= $oeuvre['title']; ?></h2>
                            <p class="description"><?= $oeuvre['artist']; ?></p>
                        </a>
                    </article>
            <?php
            }
            ?>
        </div>
    <?php 
    include_once 'includes/footer.php';
    
