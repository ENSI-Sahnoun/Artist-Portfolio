# Site Portfolio de Mohamed Sahnoun

Site portfolio et galerie en ligne pour Mohamed Sahnoun, sculpteur et artiste visuel tunisien. Le projet présente sa biographie, son parcours artistique, ses expositions, sa reconnaissance médiatique, ses acquisitions institutionnelles et un catalogue de sculptures en bois et en marbre.

# Live site : https://sahnounmohamed.up.railway.app/

## Aperçu

Ce projet est principalement un site HTML/CSS statique avec du JavaScript léger pour quelques interactions et PHP pour la gestion de la base de données. Chaque page possède sa propre structure, tout en partageant une navigation, un pied de page et une identité visuelle commune.

Le site est prévu pour fonctionner avec un serveur local XAMPP :

```text
http://localhost/portfolio/
```

## Structure du projet

```text
portfolio/
├── index.html                  # Page d'accueil, hero, biographie, aperçu des catégories, presse
├── cv.html                     # CV de l'artiste, biographie, contact, chronologie des expositions
├── testimonials.html           # Sources presse, reconnaissance et formulaire d'avis
├── gov.html                    # Galerie des achats/acquisitions institutionnelles
├── exhibitions.html            # Galerie des expositions et oeuvres sélectionnées
├── catalog.html                # Boutique/catalogue avec panier, modales produit et formulaire d'achat
├── thank-you.html              # Page de confirmation après une demande d'achat
├── sorry.html                  # Page d'erreur après une demande d'achat
├── buying_process.php          # Traitement PHP du formulaire d'achat et insertion dans la table clients
├── testimonial_process.php     # Traitement PHP du formulaire d'avis et insertion dans la table testimonials
├── css/
│   ├── style.css               # Styles globaux : navigation, footer, typographie, sections communes
│   ├── catalog.css             # Boutique, cartes produits, panier, modales, checkout
│   ├── cv.css                  # Page CV et chronologie animée
│   ├── exhibitions.css         # Page expositions
│   ├── gov.css                 # Galerie institutionnelle
│   └── testimonials.css        # Cartes presse, sources, formulaire d'avis, notifications
└── images/
    ├── wood/                   # Images des oeuvres en bois
    ├── marble/                 # Images des oeuvres en marbre
    ├── gov/                    # Images des collections institutionnelles
    └── *.png, *.jpg, *.webp    # Assets hero, portrait, presse, expositions, logo et icônes
```

## Pages principales

### Accueil (`index.html`)

- Grand visuel hero avec image de fond et titre de l'artiste.
- Présentation courte de Mohamed Sahnoun.
- Section biographique avec composition d'images.
- Aperçu des catégories d'oeuvres : bois, marbre et polystyrène.
- Cartes de mise en avant presse/média.
- Liens de navigation vers le CV, les témoignages, les achats gouvernementaux, les expositions et la boutique.

### CV (`cv.html`)

- Portrait de l'artiste, biographie et informations de contact.
- Chronologie des expositions générée par un tableau JavaScript `exhibitions`.
- Année collante qui se met à jour pendant le défilement.

### Témoignages (`testimonials.html`)

- Cartes de reconnaissance et de presse avec liens vers les sources externes.
- Liste complète des sources institutionnelles et médiatiques.
- Formulaire d'avis avec nom, email, localisation, type d'oeuvre, note, commentaire, détails de visite et médium préféré.
- Notifications visuelles basées sur les paramètres d'URL `?success` et `?error`.

### Achats gouvernementaux (`gov.html`)

- Présentation des acquisitions institutionnelles.
- Galerie d'oeuvres intégrées à des collections publiques ou officielles.
- Mise en page de type masonry pour les images.

### Expositions (`exhibitions.html`)

- Section hero dédiée aux expositions.
- Galerie d'oeuvres sélectionnées avec images `images/sympo-*.jpg`.
- Boutons d'information ou de contact affichés au survol.

### Boutique / Catalogue (`catalog.html`)

- Catalogue divisé en deux catégories : Wood et Marble.
- Cartes produit avec image, nom, matériau, dimensions et prix en TND.
- Boutons de navigation rapide entre catégories.
- Fonction "load more" pour afficher progressivement les produits cachés.
- Modale de détail produit alimentée par l'objet JavaScript `products`.
- Panier côté client avec ajout, suppression, total et sous-total.
- Modale de checkout envoyant les informations d'achat vers `buying_process.php`.

## Fonctionnalités

- Navigation commune sur les pages principales.
- Design responsive avec CSS spécifique par page.
- Catalogue interactif en JavaScript vanilla.
- Modales animées pour les produits, le panier et le checkout.
- Formulaire d'achat avec nom, email, téléphone, ville/localisation, adresse, notes et produits sélectionnés.
- Formulaire d'avis avec animation d'ouverture/fermeture.
- Notifications de succès/erreur sur la page témoignages.
- Chronologie interactive sur la page CV.

## Base de données

```text
Nom de la base de données : portfolio
```

Tables :

| Table | Rôle | Champs liés aux formulaires |
| --- | --- | --- |
| `clients` | Stocker les demandes d'achat envoyées depuis la boutique | `id`, `name`, `email`, `phone`, `location`, `carte`, `adresse`, `date`, `note` |
| `testimonials` | Stocker les avis envoyés depuis la page témoignages | `id`, `name`, `email`, `location`, `artwork_type`, `rating`, `review`, `visitation`, `date`, `medium` |


## Styles

Le style du site est réparti comme suit :

- `css/style.css` : base commune, typographie, navigation, boutons, footer et sections partagées.
- `css/catalog.css` : catalogue, cartes produit, panier, modales et formulaire d'achat.
- `css/cv.css` : page CV et chronologie.
- `css/testimonials.css` : pages presse, sources, formulaire d'avis et notifications.
- `css/gov.css` : page des acquisitions institutionnelles.
- `css/exhibitions.css` : page des expositions.

## Lancement local avec XAMPP

1. Placer le projet dans :

```text
c:\xampp\htdocs\portfolio
```

2. Démarrer Apache depuis le panneau de contrôle XAMPP.
3. Ouvrir le site dans le navigateur :

```text
http://localhost/portfolio/index.html
```

Les pages HTML peuvent aussi être ouvertes directement dans le navigateur, mais les routes `.php` et les formulaires doivent être testés via Apache.

## Notes d'implémentation

- Aucun système de build ou gestionnaire de paquets n'est nécessaire.
- Le JavaScript est actuellement intégré directement dans les pages HTML concernées.
- Les fichiers `.php` traitent les soumissions des formulaires et enregistrent les données dans la base `portfolio`.
- Les données produits sont présentes à la fois dans le HTML du catalogue et dans l'objet JavaScript `products`. Il faut garder les deux parties synchronisées lors de l'ajout ou la modification d'un produit.

