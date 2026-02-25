<?php
// Déterminer si on est en mode édition ou ajout
$isEditMode = isset($artwork) && $artwork !== null;
$formAction = $isEditMode ? "dashboard.php?action=update_artworks&id=" . $artwork->getId() : "dashboard.php?action=add_artwork";
$submitLabel = $isEditMode ? "Mettre à jour l'œuvre" : "Ajouter l'œuvre";
?>

<form class="artwork-form" method="POST" action="<?= $formAction ?>" enctype="multipart/form-data">
    <div class="art-preview">
    <?php if ($isEditMode): ?>
        <input type="hidden" name="id" value="<?= $artwork->getId() ?>">
    <?php endif;
        if ($isEditMode && $artwork->getPhoto()): ?>
            <div class="current-photo">
                <img src="<?= htmlspecialchars($artwork->getPhoto()) ?>" alt="Photo actuelle" class="form-preview">
                <p class="form-help">Photo actuelle</p>
            </div>
    <?php endif;
    ?>
    </div>
    <div class="form-data">
        <div class="form-row">
            <div class="form-group">
                <label for="title">Titre de l'œuvre</label>
                <input type="text" id="title" name="title" class="form-input" placeholder="Ex: La Joconde" minlength="1" maxlength="100"
                    value="<?= $isEditMode ? htmlspecialchars($artwork->getTitle()) : '' ?>" required>
            </div>

            <div class="form-group">
                <label for="artist">Artiste</label>
                <input type="text" id="artist" name="artist" class="form-input" placeholder="Ex: Léonard de Vinci" minlength="2" maxlength="100"
                    value="<?= $isEditMode ? htmlspecialchars($artwork->getArtist()) : '' ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="photo">Photo de l'œuvre</label>
            <input type="file" id="photo" name="photo" class="form-input-file" accept="image/*" <?= $isEditMode ? '' : 'required' ?>>
            <small class="form-help">Formats acceptés : JPG, PNG, GIF<?= $isEditMode ? ' (laisser vide pour conserver la photo actuelle)' : '' ?></small>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-textarea" rows="9" placeholder="Décrivez l'œuvre..." minlength="1" maxlength="1000"><?= $isEditMode ? htmlspecialchars($artwork->getDescription()) : '' ?></textarea>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn-submit"><?= $submitLabel ?></button>
        <button type="reset" class="btn-reset">Réinitialiser</button>
        <?php if ($isEditMode): ?>
            <a href="dashboard.php?action=update_artworks" class="btn-cancel">Annuler</a>
        <?php endif; ?>
    </div>
</form>