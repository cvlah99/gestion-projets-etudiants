# Java Integration Guide — PFS Project
## Gestion des Tâches (Task Management)

---

## 1. Project Architecture Overview

### How C++ is currently integrated
The existing C++ programs work as follows:
- **PHP calls C++ exe via `shell_exec()`**
- C++ reads arguments, inserts into MySQL, prints a result
- PHP reads the printed result

Example in `creer_encadrant.php`:
```php
$commande = '"' . $exe . '" ' . escapeshellarg($nom) . " " . escapeshellarg($prenom) . " " . $id_admin;
$cle = trim(shell_exec($commande));
```

### How Java is integrated (same pattern)
Java works **exactly the same way**:
- **PHP calls Java via `shell_exec()`** using `java -cp ... ClassName args...`
- Java reads `args[]`, inserts into MySQL, prints a result
- PHP reads the printed result

---

## 2. Files Added / Modified

### New Java Files (`/java/`)
| File | Role |
|------|------|
| `Tache.java` | Data class — stores one task (like `Encadrant.h` in C++) |
| `DatabaseConnector.java` | All database queries for tasks (like `DatabaseConnector.cpp`) |
| `MainTache.java` | Entry point for creating a task (like `main_encadrant.cpp`) |
| `MainValiderTache.java` | Entry point for accepting a task |
| `MainRefuserTache.java` | Entry point for refusing a task |
| `MainSoumission.java` | Entry point for recording a student's submission |
| `MainStats.java` | Entry point for getting admin statistics |
| `migration_tache.sql` | SQL to create the `tache` table |
| `compile.bat` | Windows compile script |
| `compile.sh` | Linux/Mac compile script |

### New PHP Files (`/taches/`)
| File | Role |
|------|------|
| `creer_tache_form.php` | Form page for encadrant to create a task |
| `ajouter_tache.php` | Processes form submission, calls `MainTache.java` |
| `liste_taches_encadrant.php` | Task tracking page (shows all tasks + submissions) |
| `valider_tache.php` | Calls `MainValiderTache.java` (accept) |
| `refuser_tache.php` | Calls `MainRefuserTache.java` (refuse) |
| `tache_etudiant.php` | Student view — shows assigned tasks |
| `soumettre_tache.php` | Processes student file upload, calls `MainSoumission.java` |
| `taches.css` | Styles for all task pages |

### Modified PHP Files
| File | Change |
|------|--------|
| `admin/dashboard_admin.php` | Added task stats section + task table (original code untouched) |
| `admin/dashboard_admin_original.php` | Backup of original file |

---

## 3. Database Change

### New table: `tache`

Run this SQL once on your database:
```sql
mysql -u root -p gestion_de_project < java/migration_tache.sql
```

The table structure:
```
Id_Tache              INT AUTO_INCREMENT (primary key)
titre_tache           VARCHAR(255)
description_tache     TEXT
date_limite_tache     DATE
Id_Groupe             INT (foreign key → groupe)
Id_Encad              INT (foreign key → encadrant)
statut_tache          ENUM('en attente', 'soumis', 'accepte', 'refuse')
fichier_soumis        VARCHAR(255)
date_creation_tache   DATETIME
date_soumission_tache DATETIME
```

---

## 4. Setup Instructions

### Step 1 — Run the SQL migration
```bash
mysql -u root -p gestion_de_project < java/migration_tache.sql
```

### Step 2 — Download the MySQL JDBC driver
1. Go to: https://dev.mysql.com/downloads/connector/j/
2. Select "Platform Independent" → Download the ZIP
3. Unzip and copy the `.jar` file into your `java/` folder
4. Rename it to `mysql-connector.jar`

> The file should be at: `gestion-projets-etudiants/java/mysql-connector.jar`

### Step 3 — Compile the Java files

**Windows:**
```
cd gestion-projets-etudiants\java
compile.bat
```

**Linux / Mac:**
```bash
cd gestion-projets-etudiants/java
bash compile.sh
```

After compiling, you should see `.class` files alongside the `.java` files.

### Step 4 — Make sure Java is in your server's PATH
Test that PHP can run Java:
```php
// Quick test PHP file
<?php echo shell_exec('java -version 2>&1'); ?>
```
If this shows the Java version, everything is working.

### Step 5 — Create the uploads folder for tasks
```bash
mkdir -p gestion-projets-etudiants/uploads/taches
chmod 755 gestion-projets-etudiants/uploads/taches
```

---

## 5. How to Test the Full Workflow

### Test 1 — Create a task (encadrant)
1. Log in as encadrant
2. Go to: `/PFS/taches/creer_tache_form.php`
3. Fill in the form (title, description, select a group, set date)
4. Click "Créer la tâche"
5. You should be redirected to the task list with a success message

### Test 2 — View and submit (student)
1. Log in as a student (whose group has a task)
2. Go to: `/PFS/taches/tache_etudiant.php`
3. You should see the task assigned to your group
4. Upload a PDF file and click "Envoyer la soumission"
5. The task status should change to "En cours de révision"

### Test 3 — Accept or refuse (encadrant)
1. Log in as encadrant
2. Go to: `/PFS/taches/liste_taches_encadrant.php`
3. Find the task with status "Soumis"
4. Click "Accepter" or "Refuser"
5. The status badge should update

### Test 4 — Admin statistics
1. Log in as admin
2. Go to: `/PFS/admin/dashboard_admin.php`
3. Scroll to the "Statistiques des Tâches (Java)" section
4. The counters should reflect the tasks in the database

### Test Java manually from command line:
```bash
cd java

# Test stats
java -cp ".;mysql-connector.jar" MainStats

# Test creating a task
java -cp ".;mysql-connector.jar" MainTache "Mon titre" "Description ici" "2025-06-30" 1 1

# Test validating task #5
java -cp ".;mysql-connector.jar" MainValiderTache 5

# Test refusing task #5
java -cp ".;mysql-connector.jar" MainRefuserTache 5
```
(On Linux, replace `;` with `:` in the classpath)

---

## 6. Navigation Links to Add

Add these links to your encadrant sidebar (in `dashboard_enseignant.php`):
```html
<a href="/PFS/taches/liste_taches_encadrant.php" class="nav_link">
    <i class="fa-solid fa-list-check"></i>
    <span>Mes Tâches</span>
</a>
<a href="/PFS/taches/creer_tache_form.php" class="nav_link">
    <i class="fa-solid fa-plus"></i>
    <span>Nouvelle tâche</span>
</a>
```

The student dashboard already has the link:
```html
<a class="nav-link" href="/PFS/taches/tache_etudiant.php">Tâche à soumettre</a>
```
(This was already in `dashboard.php` — it now points to the real page)

---

## 7. Classpath Note (Windows vs Linux)

PHP code uses `PATH_SEPARATOR` which is automatically:
- `;` on Windows
- `:` on Linux/Mac

This is already handled in the PHP files:
```php
$cp = $javaDir . PATH_SEPARATOR . $javaDir . "/mysql-connector.jar";
```

---

## 8. Summary

The Java integration follows the **exact same pattern** as the C++ integration:
1. PHP prepares arguments
2. PHP calls the Java program via `shell_exec()`
3. Java connects to MySQL, does its work, prints the result
4. PHP reads and processes the result

No existing files were broken. The C++ programs for groups and encadrants still work exactly as before.
