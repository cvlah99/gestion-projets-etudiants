import java.sql.Connection;
import java.sql.PreparedStatement;

public class MainSoumission {

    public static void main(String[] args) {

        // 1. Vérifier que PHP a envoyé les 2 arguments
        if (args.length < 2) {
            System.out.println("-1");
            return;
        }

        // 2. Récupérer les arguments
        int    idTache  = Integer.parseInt(args[0]);
        String fichier  = args[1];

        try {
            // 3. Connexion à la BDD
            Connection conn = DatabaseConnector.getConnection();

            // 4. Préparer la requête UPDATE
            String sql = "UPDATE tache SET fichier = ?, statut = 'soumis' WHERE Id_Tache = ?";

            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setString(1, fichier);
            stmt.setInt(2,    idTache);

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