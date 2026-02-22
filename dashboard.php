<?php
require_once __DIR__ . '/class/ArtworkController.php';
include_once 'includes/header.php';
$action = $_GET['action'] ?? null;
?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Bienvenue sur le dashboard de The Artbox. Ici, vous pouvez gérer vos œuvres d'art, ajouter de nouvelles créations, supprimer des oeuvres et modifier les informations existantes.</p>
    <p>Utilisez les liens ci-dessous pour naviguer vers les différentes sections de gestion :</p>
    <ul>
        <li><a href="dashboard.php?action=add_artwork" class="<?= $action === 'add_artwork' ? 'active' : '' ?>">Ajouter une œuvre</a></li>
        <li><a href="dashboard.php?action=delete_artwork" class="<?= $action === 'delete_artwork' ? 'active' : '' ?>">Supprimer une œuvre</a></li>
        <li><a href="dashboard.php?action=update_artworks" class="<?= $action === 'update_artworks' ? 'active' : '' ?>">Gérer les œuvres existantes</a></li>
    </ul>
</div>
<div class="dashboard-content">
<?php
switch ($action) {
    case 'add_artwork':
        include_once 'includes/add_artwork.php';
        break;
    case 'delete_artwork':
        include_once 'includes/delete_artwork.php';
        break;
    case 'update_artworks':
        include_once 'includes/update_artwork.php';
        break;
    default:
        echo "<p>Veuillez sélectionner une action dans le menu ci-dessus pour gérer vos œuvres d'art.</p>";
        break;
}
?>
</div>
<?php
include_once 'includes/footer.php';
    