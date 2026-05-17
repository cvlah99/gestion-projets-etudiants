import java.sql.Connection;
import java.sql.PreparedStatement;

public class MainRefuserTache {

    public static void main(String[] args) {

        // 1. Vérifier que PHP a envoyé l'ID de la tâche
        if (args.length < 1) {
            System.out.println("-1");
            return;
        }

        // 2. Récupérer l'ID de la tâche
        int idTache = Integer.parseInt(args[0]);

        try {
            // 3. Connexion à la BDD
            Connection conn = DatabaseConnector.getConnection();

            // 4. Préparer la requête UPDATE
            String sql = "UPDATE tache SET statut = 'refuse' WHERE Id_Tache = ?";

            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setInt(1, idTache);

            // 5. Exécuter
            stmt.executeUpdate();

            // 6. Retourner succès à PHP
            System.out.println("1");

            conn.close();

        } catch (Exception e) {
            System.out.println("-1");
        }
    }
}