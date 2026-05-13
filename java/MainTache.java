// ============================================================
// MainTache.java
// Entry point for creating a task (tâche).
//
// This is the Java equivalent of main_encadrant.cpp
// PHP calls this via shell_exec(), just like it calls encadrant.exe
//
// Usage:
//   java -cp ".;mysql-connector.jar" MainTache <titre> <description> <dateLimite> <idGroupe> <idEncadrant>
//
// Output:
//   Prints the new task ID to stdout (for PHP to read)
//   Prints "ERREUR" if something goes wrong
// ============================================================

public class MainTache {

    public static void main(String[] args) {

        // Check we received exactly 5 arguments
        // (same pattern as main_encadrant.cpp checking argc == 4)
        if (args.length != 5) {
            System.out.println("ERREUR: arguments manquants");
            System.exit(1);
        }

        // Read the arguments passed by PHP
        String titre       = args[0]; // task title
        String description = args[1]; // task description
        String dateLimite  = args[2]; // deadline date
        int idGroupe       = Integer.parseInt(args[3]); // group ID
        int idEncadrant    = Integer.parseInt(args[4]); // encadrant ID

        // Create a Tache object to hold the data
        // Same idea as creating an Encadrant object in C++
        Tache t = new Tache(titre, description, dateLimite, idGroupe, idEncadrant);

        // Connect to the database and insert the task
        DatabaseConnector db = new DatabaseConnector();
        int newId = db.insererTache(
            t.getTitre(),
            t.getDescription(),
            t.getDateLimite(),
            t.getIdGroupe(),
            t.getIdEncadrant()
        );

        // Print the result for PHP to read
        if (newId > 0) {
            System.out.println(newId); // success: print the new task ID
        } else {
            System.out.println("ERREUR"); // something went wrong
        }

    }

}
