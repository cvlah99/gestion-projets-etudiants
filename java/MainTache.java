import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class MainTache {

    public static void main(String[] args) {

        // 1. Vérifier que PHP a bien envoyé les 5 arguments
        if (args.length < 5) {
            System.out.println("-1");
            return;
        }

        // 2. Récupérer les arguments envoyés par PHP
        String titre       = args[0];
        String description = args[1];
        String dateLimite  = args[2];
        int    idGroupe    = Integer.parseInt(args[3]);
        int    idEncadrant = Integer.parseInt(args[4]);

        // 3. Créer l'objet Tache
        Tache tache = new Tache(titre, description, dateLimite, idGroupe, idEncadrant);

        try {
            // 4. Connexion à la BDD
            Connection conn = DatabaseConnector.getConnection();

            // 5. Préparer la requête INSERT
            String sql = "INSERT INTO tache (titre, DescriptionT, Date_limiteT, Id_Groupe, Id_Encad, statut) " +
                         "VALUES (?, ?, ?, ?, ?, ?)";

            PreparedStatement stmt = conn.prepareStatement(sql, PreparedStatement.RETURN_GENERATED_KEYS);
            stmt.setString(1, tache.getTitre());
            stmt.setString(2, tache.getDescription());
            stmt.setString(3, tache.getDateLimite());
            stmt.setInt(4,    tache.getIdGroupe());
            stmt.setInt(5,    tache.getIdEncadrant());
            stmt.setString(6, tache.getStatut());

            // 6. Exécuter la requête
            stmt.executeUpdate();

            // 7. Récupérer l'ID généré et l'afficher pour PHP
            ResultSet rs = stmt.getGeneratedKeys();
            if (rs.next()) {
                System.out.println(rs.getInt(1));
            } else {
                System.out.println("-1");
            }

            conn.close();

        } catch (Exception e) {
            System.out.println("-1");
        }
    }
}