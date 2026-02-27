<?php
session_start();
require_once __DIR__ . '/class/ArtworkDataHelper.php';
include_once 'includes/header.php';

$action = $_GET['action'] ?? null;
$dataHelper = new ArtworkDataHelper();

?>
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Bienvenue sur le dashboard de The Artbox. Ici, vous pouvez gérer vos œuvres d'art, ajouter de nouvelles créations, supprimer des oeuvres et modifier les informations existantes.</p>
    <p>Utilisez les liens ci-dessous pour naviguer vers les différentes sections de gestion :</p>
    <ul>
        <li><a href="dashboard.php?action=add_artwork" class="<?= $action === 'add_artwork' ? 'active' : '' ?>">Ajouter une œuvre</a></li>
        <li><a href="dashboard.php?action=update_artwork" class="<?= $action === 'update_artwork' ? 'active' : '' ?>">Gérer les œuvres existantes</a></li>
        <li><a href="dashboard.php?action=delete_artwork" class="<?= $action === 'delete_artwork' ? 'active' : '' ?>">Supprimer une œuvre</a></li>
    </ul>
</div>
<div class="dashboard-content">
<?php
// Toute la logique est gérée par le DataHelper
$dataHelper->handleAction($action);
?>
</div>
<?php
include_once 'includes/footer.php';
    