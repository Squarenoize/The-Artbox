<?php
require_once __DIR__ . '/class/ArtworkController.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    ?>
    <span class="error">ID de l'oeuvre non spécifié ou invalide.</span>
    <?php return;
}

$id = intval($_GET['id']);

$controller = new ArtworkController();
$artwork = $controller->getArtwork($id);

if(!$artwork || $artwork->getId() !== $id) {
    ?>
    <span class="error">Oeuvre non trouvée.</span>
    <?php return;
}
 
include_once 'includes/header.php';
?>
    <article class="oeuvre-detail">
        <img src="<?php echo htmlspecialchars($artwork->getPhoto()); ?>" alt="<?php echo htmlspecialchars($artwork->getTitle()); ?>">
        <h1><?php echo htmlspecialchars($artwork->getTitle()); ?></h1>
        <h2><?php echo htmlspecialchars($artwork->getArtist()); ?></h2>
        <p><?php echo htmlspecialchars($artwork->getDescription()); ?></p>
    </article>
<?php 
include_once 'includes/footer.php'; 
