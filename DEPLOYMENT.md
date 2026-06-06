# Instructions de Déploiement FTP

Ce document contient les informations de connexion et les procédures pour déployer le projet sur le serveur de production.

> [!WARNING]
> Ce fichier est configuré dans `.gitignore` pour ne jamais être partagé sur votre dépôt Git public afin de protéger vos identifiants.

## 1. Identifiants de Connexion FTP
- **Hôte (Host)** : `successbusinessweb.com`
- **Utilisateur (User)** : `successb`
- **Mot de passe (Password)** : `3Q60Zu7ynl`
- **Répertoire de destination** : `/formation.successbusinessweb.com`
  - Le répertoire racine public web correspond à `/formation.successbusinessweb.com/public`

---

## 2. Identifiants de la Base de Données (Remote DB)
Ces valeurs sont déjà configurées dans le fichier `.env` distant :
- **Base de données** : `successb_formation`
- **Utilisateur** : `successb_formation`
- **Mot de passe** : `S93[7np72@`
- **Hôte** : `127.0.0.1`

---

## 3. Déploiement des modifications de fichiers
Pour toute modification sur les fichiers locaux (ex: contrôleurs, vues, CSS, JS), le fichier modifié doit être envoyé à son emplacement équivalent sur le serveur FTP.

### Méthode 1 : Envoi manuel (FileZilla ou autre client FTP)
1. Ouvrez votre client FTP.
2. Connectez-vous avec les identifiants ci-dessus.
3. Glissez-déposez le fichier modifié de votre dossier local vers le dossier distant équivalent.
   *Exemple : `resources/views/home.blade.php` local -> `/formation.successbusinessweb.com/resources/views/home.blade.php` distant.*

### Méthode 2 : Envoi automatisé via l'agent
Lors des sessions futures, l'agent utilisera des scripts de synchronisation ciblée pour n'envoyer que les fichiers modifiés par FTP de façon transparente.
