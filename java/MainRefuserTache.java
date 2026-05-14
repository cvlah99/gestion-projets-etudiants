// ============================================================
// MainRefuserTache.java
// Entry point for REFUSING a task submission.
//
// PHP calls this after the encadrant clicks "Refuser"
// This updates the task status to 'refuse' in the database.
//
// Usage:
//   java -cp ".;mysql-connector.jar" MainRefuserTache <idTache>
//
// Output:
//   "OK" if success, "ERREUR" if failed
// ============================================================

public class MainRefuserTache {

    public static void main(String[] args) {

        // We need exactly 1 argument: the task ID
        if (args.length != 1) {
            System.out.println("ERREUR: argument manquant");
            System.exit(1);
        }

        // Read the task ID from the argument
        int idTache = Integer.parseInt(args[0]);

        // Connect to DB and update the status to 'refuse'
        DatabaseConnector db = new DatabaseConnector();
        boolean succes = db.mettreAJourStatutTache(idTache, "refuse");

        // Print result for PHP
        if (succes) {
            System.out.println("OK");
        } else {
            System.out.println("ERREUR");
        }

    }

}
