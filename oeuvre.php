<?php
$oeuvres = include 'oeuvres.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    ?>
    <span class="error">ID de l'oeuvre non spécifié ou invalide.</span>
    <?php return;
}

$id = intval($_GET['id']);

if(!isset($oeuvres[$id])) {
    ?>
    <span class="error">Oeuvre non trouvée.</span>
    <?php return;
}

$oeuvre = $oeuvres[$id];
 
include_once 'includes/header.php';
?>
    <article class="oeuvre-detail">
        <img src="<?php echo htmlspecialchars($oeuvre['img']); ?>" alt="<?php echo htmlspecialchars($oeuvre['title']); ?>">
        <h1><?php echo htmlspecialchars($oeuvre['title']); ?></h1>
        <h2><?php echo htmlspecialchars($oeuvre['artist']); ?></h2>
        <p><?php echo htmlspecialchars($oeuvre['description']); ?></p>
    </article>
<?php 
include_once 'includes/footer.php'; 
