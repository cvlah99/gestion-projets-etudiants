// ============================================================
// MainStats.java
// Entry point for getting task statistics (for admin dashboard).
//
// PHP calls this to get counts of tasks by status.
// Outputs a simple format that PHP can parse.
//
// Usage:
//   java -cp ".;mysql-connector.jar" MainStats
//
// Output format (one line per stat):
//   total:12
//   accepte:5
//   refuse:2
//   soumis:3
//   en attente:2
// ============================================================

public class MainStats {

    public static void main(String[] args) {

        // Connect to DB and count tasks by status
        DatabaseConnector db = new DatabaseConnector();

        // Count each category
        int total      = db.compterTachesParStatut("total");
        int acceptes   = db.compterTachesParStatut("accepte");
        int refuses    = db.compterTachesParStatut("refuse");
        int soumis     = db.compterTachesParStatut("soumis");
        int enAttente  = db.compterTachesParStatut("en attente");

        // Print results in a simple format for PHP to parse
        // Each line: key:value
        System.out.println("total:" + total);
        System.out.println("accepte:" + acceptes);
        System.out.println("refuse:" + refuses);
        System.out.println("soumis:" + soumis);
        System.out.println("en_attente:" + enAttente);

    }

}
