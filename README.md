# Cart Pricing Kata

## Déscription

Les corrections ont été réalisées de manière incrémentale, test par test, avec une attention particulière portée à la qualité du code, à la validation des entrées et à l'automatisation via une chaîne d’intégration continue.

---

## Règles métier couvertes
- Les prix sont manipulés **en centimes (int)**.
- La **TVA de 20 %** est appliquée **après la remise**.
- Une remise de **20 %** est appliquée uniquement le **Black Friday** (dernier vendredi de novembre).
- Les produits et quantités invalides sont rejetés.
- Le total TTC est toujours **un entier et non négatif**.

---

## Corrections fonctionnelles réalisées

### 1. Gestion des prix et calculs
- Correction de la gestion des prix : les prix étaient manipulés en euros, ils sont désormais traités exclusivement **en centimes**.
- Correction du type du sous-total (`$subtotal`) : passage de `float` à `int` pour éviter les erreurs de précision.
- Correction de la fonction `totalCents` afin d’appliquer la **remise avant la TVA**, conformément aux règles métier.

---

### 2. Comparaison des objets
- Correction de la méthode de comparaison entre deux produits :
  - remplacement d'une affectation incorrecte par une **comparaison stricte (`===`)**.

---

### 3. Black Friday et gestion des dates
- Correction de la fonction `getDiscountPercent` :
  - utilisation du fuseau horaire **Europe/Paris** au lieu de `UTC`
  - correction de la logique de détection du **dernier vendredi de novembre**
  - remise par défaut fixée à **0 %** (au lieu de 5 %)

---

### 4. Validation des entrées
- Ajout de validations métier :
  - rejet des produits avec un identifiant vide
  - rejet des prix négatifs
  - rejet des quantités nulles ou négatives lors de l'ajout au panier
- Levée d'exceptions `InvalidArgumentException` conformément aux tests unitaires.

---

## Gestion du dépôt
- Ajout d'un fichier `.gitignore` pour exclure :
  - le dossier `vendor/`
  - les fichiers temporaires de PHPUnit

---

## Qualité de code
- Intégration de **PHP_CodeSniffer (PHPCS)** avec le standard **PSR-12**
- Correction automatique du style de code à l'aide de `phpcbf`
- Ajout de **PHPStan** pour l'analyse statique du code

---

## Sécurité des dépendances
- Vérification de la sécurité des dépendances via :
  - `composer validate --strict`
  - `composer audit`

---

## Intégration continue (CI)
Une chaîne d'intégration continue a été mise en place avec **GitHub Actions**.

### La pipeline exécute automatiquement :
- la validation du fichier `composer.json`
- l'audit de sécurité des dépendances
- les contrôles de qualité de code (PHPCS, PHPStan)
- l'exécution des tests unitaires PHPUnit

La CI est déclenchée **uniquement lors des Pull Requests**, afin de valider les changements avant fusion.