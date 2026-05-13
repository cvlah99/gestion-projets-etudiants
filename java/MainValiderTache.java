// ============================================================
// MainValiderTache.java
// Entry point for ACCEPTING (validating) a task submission.
//
// PHP calls this after the encadrant clicks "Accepter"
// This updates the task status to 'accepte' in the database.
//
// Usage:
//   java -cp ".;mysql-connector.jar" MainValiderTache <idTache>
//
// Output:
//   "OK" if success, "ERREUR" if failed
// ============================================================

public class MainValiderTache {

    public static void main(String[] args) {

        // We need exactly 1 argument: the task ID
        if (args.length != 1) {
            System.out.println("ERREUR: argument manquant");
            System.exit(1);
        }

        // Read the task ID from the argument
        int idTache = Integer.parseInt(args[0]);

        // Connect to DB and update the status
        DatabaseConnector db = new DatabaseConnector();
        boolean succes = db.mettreAJourStatutTache(idTache, "accepte");

        // Print the result for PHP
        if (succes) {
            System.out.println("OK");
        } else {
            System.out.println("ERREUR");
        }

    }

}
