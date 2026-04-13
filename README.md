# 📝 Phrase du Jour

Projet Symfony 7 réalisé en autonomie dans le cadre de la formation **DWWM** à Hunik Academy.

## 📋 Description

Application web permettant de découvrir et partager des phrases du jour. Les utilisateurs peuvent consulter les phrases, les liker et les commenter. Un espace d'administration permet de gérer les phrases.

## 🚀 Fonctionnalités

- 📖 Affichage des phrases du jour par date décroissante
- 🔍 Découverte d'une phrase du jour avec sa catégorie
- 👍 Système de like (1 like par utilisateur connecté)
- 💬 Commentaires sur les phrases (utilisateurs connectés)
- 🔐 Authentification (connexion / enregistrement)
- 🛡️ Espace administration (ROLE_ADMIN)
  - Ajout d'une phrase
  - Modification d'une phrase
  - Suppression avec confirmation et protection CSRF

## 🛠️ Stack technique

- **Framework** : Symfony 7.4
- **Langage** : PHP 8.2
- **Base de données** : MySQL 8.0
- **ORM** : Doctrine
- **Templates** : Twig
- **CSS** : Bootstrap 5 / Bootswatch Brite
- **Environnement** : Docker (Nginx, PHP-FPM, MySQL, phpMyAdmin)

## 📦 Prérequis

- Docker
- Docker Compose
- Git

## ⚙️ Installation

### 1. Cloner le projet

```bash
git clone https://github.com/CyrilDavid27370/phrase_du_jour.git
cd phrase_du_jour
```

### 2. Lancer les conteneurs Docker

```bash
docker compose up -d
```

### 3. Installer les dépendances Symfony

```bash
docker compose exec php bash
composer install
```

### 4. Configurer la base de données

Dans le fichier `app/.env`, vérifier la ligne `DATABASE_URL` :

```env
DATABASE_URL="mysql://admin:password@mysql:3306/sentence?serverVersion=8.0.45&charset=utf8mb4"
```

### 5. Créer la base de données et les tables

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 6. Charger les données de test

```sql
-- Catégories
INSERT INTO category (id, name, color) VALUES
(1, 'Humour', 'warning'),
(2, 'Philosophie', 'light'),
(3, 'Absurde', 'primary'),
(4, 'Citation', 'info');

-- Phrases
INSERT INTO sentence (id, content, created_at, category_id, likes) VALUES
(1, 'Je parle tout seul parce que je suis la seule personne intéressante.', '2026-04-05 10:00:00', 1, 5),
(2, 'Je pense donc je suis.', '2026-04-06 10:00:00', 2, 10),
(3, 'Les licornes dominent le monde en secret.', '2026-04-07 10:00:00', 3, 2),
(4, 'La vie est un mystère qu il faut vivre.', '2026-04-08 10:00:00', 4, 7),
(5, 'Pourquoi faire aujourd hui ce qu on peut faire demain ?', '2026-04-09 10:00:00', 1, 3);

-- Utilisateurs
INSERT INTO user (id, username, roles, password) VALUES
(1, 'admin', '["ROLE_ADMIN"]', '$2y$13$6kwkB37KQn7Ui/JoamxTCeG20X8N/elRuGIW95McaJli6xWvCJdbO'),
(2, 'Hakim', '["ROLE_USER"]', '$2y$13$HhPQIYus52oyPUwKlm7ea.fO588L1PPOigcVpavrlc8o4UIGZkNi.'),
(3, 'Mohand', '["ROLE_USER"]', '$2y$13$HhPQIYus52oyPUwKlm7ea.fO588L1PPOigcVpavrlc8o4UIGZkNi.'),
(4, 'Cyril', '["ROLE_USER"]', '$2y$13$HhPQIYus52oyPUwKlm7ea.fO588L1PPOigcVpavrlc8o4UIGZkNi.');
```

## 🌐 Accès

| URL | Description |
|-----|-------------|
| `http://localhost:8080` | Application web |
| `http://localhost:8081` | phpMyAdmin |

## 👤 Comptes de test

| Utilisateur | Mot de passe | Rôle |
|-------------|-------------|------|
| `admin` | `admin` | ROLE_ADMIN |
| `Hakim` | `toto123` | ROLE_USER |
| `Mohand` | `toto123` | ROLE_USER |
| `Cyril` | `toto123` | ROLE_USER |

## 🗂️ Structure du projet

```
phrase_du_jour/
├── app/                        # Application Symfony
│   ├── src/
│   │   ├── Controller/
│   │   │   ├── AdminController.php
│   │   │   ├── HomeController.php
│   │   │   ├── SentenceController.php
│   │   │   ├── SecurityController.php
│   │   │   └── RegistrationController.php
│   │   ├── Entity/
│   │   │   ├── Category.php
│   │   │   ├── Comment.php
│   │   │   ├── Like.php
│   │   │   ├── Sentence.php
│   │   │   └── User.php
│   │   └── Form/
│   │       ├── CommentType.php
│   │       ├── RegistrationFormType.php
│   │       └── SentenceType.php
│   └── templates/
│       ├── admin/
│       ├── home/
│       ├── registration/
│       ├── security/
│       ├── sentence/
│       └── base.html.twig
├── docker/
│   ├── nginx/
│   │   └── nginx.conf
│   └── php/
│       └── Dockerfile
└── docker-compose.yml
```

## 📚 Commandes utiles

```bash
# Démarrer les conteneurs
docker compose up -d

# Arrêter les conteneurs
docker compose down

# Entrer dans le conteneur PHP
docker compose exec php bash

# Vider le cache Symfony
php bin/console cache:clear

# Créer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate
```

## 👨‍💻 Auteur

**Cyril David** — Formation DWWM @ Hunik Academy