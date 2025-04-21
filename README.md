# 🎵 Spotimika

Spotimika est une base de projet construite avec **Symfony**, prête à l'emploi pour développer une API ou une application backend robuste et évolutive.

## 🧰 Stack technique

- **PHP** (Symfony)
- **Docker** / Docker Compose
- **Doctrine ORM**
- **PHPUnit** pour les tests
- Configuration prête pour l’environnement de dev/test/prod

## ⚙️ Installation

### Prérequis

- Docker et Docker Compose
- PHP 8.2+
- Composer

### Lancer le projet en local

```bash
cd base_de_projet
cp .env .env.local
docker compose up --build -d
```

L'application Symfony sera disponible à l'adresse : `http://localhost:8000`

### Accéder au container

```bash
docker exec -it spotimika_app bash
```

### Installer les dépendances

```bash
composer install
```

### Lancer les tests

```bash
php bin/phpunit
```

## 🧪 Structure du projet

```
base_de_projet/
├── bin/               # Commandes Symfony
├── config/            # Fichiers de configuration
├── public/            # Dossier web (DocumentRoot)
├── src/               # Code source PHP
├── tests/             # Tests unitaires
├── .env               # Variables d’environnement
├── compose.yaml       # Stack Docker
```


