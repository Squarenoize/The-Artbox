<?php
if (!$artwork) {
    ?>
    <div class="delete-container">
        <img src="assets/404.png" alt="Œuvre introuvable" class="art-preview">
        <div>
            <h2>Œuvre introuvable</h2>
            <p>Désolé, l'œuvre que vous essayez de supprimer n'existe pas ou a déjà été supprimée.</p>
            <a href='dashboard.php?action=delete_artwork' class='btn-cancel'>Retour à la liste</a>
        </div>
    </div>
    <?php
    return;
}
?>
<div class="delete-container">
    <img src="<?= htmlspecialchars($artwork->getPhoto()) ?>" alt="<?= htmlspecialchars($artwork->getTitle()) ?>" class="art-preview">
    <div>
        <h2>Confirmer la suppression</h2>
        <p>Êtes-vous sûr de vouloir supprimer l'œuvre <strong><?= htmlspecialchars($artwork->getTitle()) ?></strong> de l'artiste <strong><?= htmlspecialchars($artwork->getArtist()) ?></strong> ? Cette action est irréversible.</p>
    </div>
    <form method="POST" action="dashboard.php?action=delete_artwork&id=<?= $artwork->getId() ?>" class="artwork-form">
        <div class="form-actions">
            <button type="submit" class="btn-submit">Supprimer</button>
            <a href="dashboard.php?action=delete_artwork" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>