<?php
require_once __DIR__ . '/class/ArtworkDataHelper.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    ?>
    <span class="error">ID de l'oeuvre non spécifié ou invalide.</span>
    <?php return;
}

$id = intval($_GET['id']);

$dataHelper = new ArtworkDataHelper();
$artwork = $dataHelper->getArtwork($id);

if(!$artwork || $artwork->getId() !== $id) {
    ?>
    <span class="error">Oeuvre non trouvée.</span>
    <?php return;
}
 
include_once 'includes/header.php';
?>
    <article class="oeuvre-detail">
        <img src="<?php echo htmlspecialchars($artwork->getPhoto(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?>">
        <h1><?php echo htmlspecialchars($artwork->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h1>
        <h2><?php echo htmlspecialchars($artwork->getArtist(), ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><?php echo htmlspecialchars($artwork->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
    </article>
<?php 
include_once 'includes/footer.php'; 
