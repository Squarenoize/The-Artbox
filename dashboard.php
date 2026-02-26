<?php
session_start();
require_once __DIR__ . '/class/ArtworkController.php';
include_once 'includes/header.php';

$action = $_GET['action'] ?? null;
$controller = new ArtworkController();

?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Bienvenue sur le dashboard de The Artbox. Ici, vous pouvez gérer vos œuvres d'art, ajouter de nouvelles créations, supprimer des oeuvres et modifier les informations existantes.</p>
    <p>Utilisez les liens ci-dessous pour naviguer vers les différentes sections de gestion :</p>
    <ul>
        <li><a href="dashboard.php?action=add_artwork" class="<?= $action === 'add_artwork' ? 'active' : '' ?>">Ajouter une œuvre</a></li>
        <li><a href="dashboard.php?action=update_artworks" class="<?= $action === 'update_artworks' ? 'active' : '' ?>">Gérer les œuvres existantes</a></li>
        <li><a href="dashboard.php?action=delete_artwork" class="<?= $action === 'delete_artwork' ? 'active' : '' ?>">Supprimer une œuvre</a></li>
    </ul>
</div>
<div class="dashboard-content">
<?php
switch ($action) {
    case 'add_artwork':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        }
        include_once 'includes/form_artwork.php';
        break;
    case 'update_artworks':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        }
        $artworkId = $_GET['id'] ?? null;

        if (!$artworkId) {
            include_once 'includes/artwork_list.php';
        } else {
            $artwork = $controller->getArtwork($artworkId);
            if (!$artwork) {
                echo "<div class='dashboard-message error'>Oeuvre introuvable.</div>";
                include_once 'includes/artwork_list.php';
                break;
            }
            include_once 'includes/form_artwork.php';
        }
        break;
    case 'delete_artwork':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $deleteSuccess = $controller->delete();
           if ($deleteSuccess) {
               header("Location: dashboard.php?action=delete_artwork");
               exit;
           }
        }

        $artworkId = $_GET['id'] ?? null;
        
        if (!$artworkId) {
            include_once 'includes/artwork_list.php';
        } else {
            $artwork = $controller->getArtwork($artworkId);
            // confirmation de suppression
            include_once 'includes/confirm_delete.php';
        }
        break;
    default:
        echo "<div class='dashboard-message info'>Veuillez sélectionner une action dans le menu ci-dessus pour gérer vos oeuvres d'art.</div>";
        break;
}
?>
</div>
<?php
include_once 'includes/footer.php';
    