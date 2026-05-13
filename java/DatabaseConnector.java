// ============================================================
// DatabaseConnector.java
// Handles all database queries for the task (tâche) system.
//
// This is the Java equivalent of DatabaseConnector.cpp
// Same database, same tables, same logic — just in Java.
//
// It uses JDBC to connect to the MariaDB/MySQL database.
// ============================================================

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class DatabaseConnector {

    // --- Database connection info ---
    // Same values as in DatabaseConnector.cpp and conexion_db.php
    private static final String DB_HOST     = "localhost";
    private static final int    DB_PORT     = 3306;
    private static final String DB_NAME     = "pfs1";
    private static final String DB_USER     = "root";
    private static final String DB_PASSWORD = "imane761944@";

    // ----------------------------------------
    // Helper method — opens a connection
    // Returns a Connection object or null if it fails
    // ----------------------------------------
    private Connection connecter() {
        try {
            // Build the JDBC URL
            String url = "jdbc:mysql://" + DB_HOST + ":" + DB_PORT + "/" + DB_NAME
                       + "?useSSL=false&serverTimezone=UTC";

            // Open connection with username and password
            Connection conn = DriverManager.getConnection(url, DB_USER, DB_PASSWORD);
            return conn;

        } catch (Exception e) {
            // If something goes wrong, print the error
            System.err.println("Erreur de connexion : " + e.getMessage());
            return null;
        }
    }

    // ============================================================
    // METHOD: insererTache
    // Inserts a new task into the database.
    // Returns the ID of the inserted task, or -1 if it fails.
    //
    // Called by: main_tache.java
    // PHP calls this via: shell_exec("java -cp ... MainTache ...")
    // ============================================================
    public int insererTache(String titre, String description, String dateLimite,
                            int idGroupe, int idEncadrant) {

        // Open the connection
        Connection conn = connecter();
        if (conn == null) {
            return -1; // connection failed
        }

        try {
            // SQL query to insert a new task
            // The ? marks are placeholders — filled in below
            String sql = "INSERT INTO tache (titre_tache, description_tache, date_limite_tache, " +
                         "Id_Groupe, Id_Encad, statut_tache, date_creation_tache) " +
                         "VALUES (?, ?, ?, ?, ?, 'en attente', NOW())";

            // Prepare the query (prevents SQL injection)
            PreparedStatement stmt = conn.prepareStatement(sql,
                PreparedStatement.RETURN_GENERATED_KEYS);

            // Fill in the placeholders
            stmt.setString(1, titre);
            stmt.setString(2, description);
            stmt.setString(3, dateLimite);
            stmt.setInt(4, idGroupe);
            stmt.setInt(5, idEncadrant);

            // Execute the query
            int rows = stmt.executeUpdate();

            if (rows > 0) {
                // Get the auto-generated ID
                ResultSet keys = stmt.getGeneratedKeys();
                if (keys.next()) {
                    int newId = keys.getInt(1);
                    conn.close();
                    return newId; // return the new task ID
                }
            }

            conn.close();
            return -1; // insert failed

        } catch (Exception e) {
            System.err.println("Erreur INSERT tache : " + e.getMessage());
            return -1;
        }
    }

    // ============================================================
    // METHOD: mettreAJourStatutTache
    // Updates the status of a task submission.
    // status can be: 'accepte' or 'refuse'
    // Returns true if it worked, false if it failed.
    //
    // Called by: MainValiderTache.java and MainRefuserTache.java
    // ============================================================
    public boolean mettreAJourStatutTache(int idTache, String statut) {

        Connection conn = connecter();
        if (conn == null) {
            return false;
        }

        try {
            String sql = "UPDATE tache SET statut_tache = ? WHERE Id_Tache = ?";

            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setString(1, statut);    // 'accepte' or 'refuse'
            stmt.setInt(2, idTache);

            int rows = stmt.executeUpdate();
            conn.close();

            // If rows > 0, the update worked
            return rows > 0;

        } catch (Exception e) {
            System.err.println("Erreur UPDATE tache : " + e.getMessage());
            return false;
        }
    }

    // ============================================================
    // METHOD: enregistrerSoumission
    // Records the file submission by a student for a task.
    // Returns true if it worked.
    //
    // Called by: MainSoumission.java
    // ============================================================
    public boolean enregistrerSoumission(int idTache, int idGroupe, String nomFichier) {

        Connection conn = connecter();
        if (conn == null) {
            return false;
        }

        try {
            // Update the task row with the submitted file name
            // and change status to 'soumis' (submitted)
            String sql = "UPDATE tache SET fichier_soumis = ?, statut_tache = 'soumis', " +
                         "date_soumission_tache = NOW() " +
                         "WHERE Id_Tache = ? AND Id_Groupe = ?";

            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setString(1, nomFichier);
            stmt.setInt(2, idTache);
            stmt.setInt(3, idGroupe);

            int rows = stmt.executeUpdate();
            conn.close();

            return rows > 0;

        } catch (Exception e) {
            System.err.println("Erreur enregistrer soumission : " + e.getMessage());
            return false;
        }
    }

    // ============================================================
    // METHOD: compterTachesParStatut
    // Returns the count of tasks with a given status.
    // Used for admin statistics.
    // ============================================================
    public int compterTachesParStatut(String statut) {

        Connection conn = connecter();
        if (conn == null) {
            return 0;
        }

        try {
            String sql;
            PreparedStatement stmt;

            if (statut.equals("total")) {
                // Count all tasks
                sql = "SELECT COUNT(*) FROM tache";
                stmt = conn.prepareStatement(sql);
            } else {
                // Count tasks with a specific status
                sql = "SELECT COUNT(*) FROM tache WHERE statut_tache = ?";
                stmt = conn.prepareStatement(sql);
                stmt.setString(1, statut);
            }

            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                int count = rs.getInt(1);
                conn.close();
                return count;
            }

            conn.close();
            return 0;

        } catch (Exception e) {
            System.err.println("Erreur COUNT tache : " + e.getMessage());
            return 0;
        }
    }

}
