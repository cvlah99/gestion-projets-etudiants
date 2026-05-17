import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnector {

    private static final String URL      = "jdbc:mysql://localhost:3306/gestion_de_project";
    private static final String USER     = "root";
    private static final String PASSWORD = "Salah@sql12";

    public static Connection getConnection() throws SQLException {
        return DriverManager.getConnection(URL, USER, PASSWORD);
    }
}