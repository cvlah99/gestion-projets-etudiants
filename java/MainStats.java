import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class MainStats {

    public static void main(String[] args) {

        // 1. Vérifier que PHP a envoyé l'ID du groupe
        if (args.length < 1) {
            System.out.println("-1");
            return;
        }

        // 2. Récupérer l'ID du groupe
        int idGroupe = Integer.parseInt(args[0]);

        try {
            // 3. Connexion à la BDD
            Connection conn = DatabaseConnector.getConnection();

            // 4. Compter les tâches par statut
            String sql = "SELECT " +
                         "COUNT(*) AS total, " +
                         "SUM(statut = 'en attente') AS en_attente, " +
                         "SUM(statut = 'soumis')     AS soumis, " +
                         "SUM(statut = 'accepte')    AS accepte, " +
                         "SUM(statut = 'refuse')     AS refuse " +
                         "FROM tache WHERE Id_Groupe = ?";

            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setInt(1, idGroupe);

            // 5. Exécuter et récupérer le résultat
            ResultSet rs = stmt.executeQuery();

            if (rs.next()) {
                // 6. Afficher les stats — PHP les récupère via shell_exec()
                System.out.println(rs.getInt("total")      + "," +
                                   rs.getInt("en_attente") + "," +
                                   rs.getInt("soumis")     + "," +
                                   rs.getInt("accepte")    + "," +
                                   rs.getInt("refuse"));
            } else {
                System.out.println("0,0,0,0,0");
            }

            conn.close();

        } catch (Exception e) {
            System.out.println("0,0,0,0,0");
        }
    }
}