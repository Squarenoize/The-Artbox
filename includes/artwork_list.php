<?php
// The variables $action and $artworks are prepared by the DataHelper
?>
<p>Veuillez sélectionner une oeuvre à <?= $action === 'update_artwork' ? "mettre à jour" : "supprimer" ?>.</p>
<?php
foreach ($artworks as $artwork) {
    ?>
    <a href='dashboard.php?action=<?= $action ?>&id=<?= $artwork->getId() ?>'>
    <div class='artwork-card'>
        <div class='artwork-header'>
            <img src="<?= htmlspecialchars($artwork->getPhoto()) ?>" alt="<?= htmlspecialchars($artwork->getTitle()) ?>" class="artwork-thumbnail">
            <h3><?= htmlspecialchars($artwork->getTitle()) ?></h3>
        </div>
        <p><?= htmlspecialchars($artwork->getArtist()) ?></p>
    </div>
    </a>
<?php
}