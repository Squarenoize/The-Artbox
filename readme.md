# The-Artbox

## Description

The-Artbox est une application PHP/MySQL permettant d'afficher des œuvres artistiques.
Les œuvres sont stockées en base de données avec leur titre, artiste, image et description.

---

## Technologies utilisées

- PHP 8.1
- MySQL 8.0
- phpMyAdmin 5.2
- Serveur local (WAMP)

---

## Base de données

Nom : artbox  
SGBD : MySQL 8.0.31  
Moteur : InnoDB  
Encodage : utf8mb4_unicode_ci  

---

## Structure de la base

### Table : works

Contient les œuvres artistiques affichées dans l'application.

| Champ          | Type         | Contraintes| Description |
|----------------|--------------|------------|-------------|
| work_id        | INT          | PRIMARY KEY, AUTO_INCREMENT | Identifiant unique de l'œuvre |
| work_title     | VARCHAR(250) | NOT NULL   | Titre de l'œuvre |
| work_artist    | VARCHAR(250) | NOT NULL   | Nom de l'artiste |
| work_photo_path| VARCHAR(250) | NOT NULL   | Chemin vers l'image de l'œuvre |
| work_desc      | TEXT         | NOT NULL   | Description détaillée |

---

## Choix techniques

- Utilisation du moteur InnoDB pour permettre l'utilisation des transactions et assurer la fiabilité des données.
- Encodage utf8mb4 pour supporter tous les caractères Unicode (accents, emojis, etc.).
- Clé primaire auto-incrémentée (work_id) pour identifier chaque œuvre de manière unique.
- Champs NOT NULL afin de garantir que chaque œuvre possède toutes les informations nécessaires.

---

## Installation de la base de données

1. Créer la base :
```sql
CREATE DATABASE artbox CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
2. Importer la structure à l'aide du fichier schema.sql 

3. Importer les données initiales à l'aide du fichier works.sql
