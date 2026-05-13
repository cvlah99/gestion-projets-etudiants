// ============================================================
// MainSoumission.java
// Entry point for recording a student's file submission.
//
// PHP calls this after the student uploads their file.
// This stores the filename and marks the task as 'soumis'.
//
// Usage:
//   java -cp ".;mysql-connector.jar" MainSoumission <idTache> <idGroupe> <nomFichier>
//
// Output:
//   "OK" if success, "ERREUR" if failed
// ============================================================

public class MainSoumission {

    public static void main(String[] args) {

        // We need 3 arguments
        if (args.length != 3) {
            System.out.println("ERREUR: arguments manquants");
            System.exit(1);
        }

        // Read arguments from PHP
        int idTache       = Integer.parseInt(args[0]); // task ID
        int idGroupe      = Integer.parseInt(args[1]); // group ID
        String nomFichier = args[2];                   // file name

        // Connect to DB and record the submission
        DatabaseConnector db = new DatabaseConnector();
        boolean succes = db.enregistrerSoumission(idTache, idGroupe, nomFichier);

        // Print result for PHP
        if (succes) {
            System.out.println("OK");
        } else {
            System.out.println("ERREUR");
        }

    }

}
