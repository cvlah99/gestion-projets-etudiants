public class Tache {

    private int id;
    private String titre;
    private String description;
    private String dateLimite;
    private String statut;
    private int idGroupe;
    private int idEncadrant;

    // Constructeur
    public Tache(String titre, String description, String dateLimite, int idGroupe, int idEncadrant) {
        this.titre       = titre;
        this.description = description;
        this.dateLimite  = dateLimite;
        this.idGroupe    = idGroupe;
        this.idEncadrant = idEncadrant;
        this.statut      = "en attente";
    }

    // Getters
    public int    getId()          { return id; }
    public String getTitre()       { return titre; }
    public String getDescription() { return description; }
    public String getDateLimite()  { return dateLimite; }
    public String getStatut()      { return statut; }
    public int    getIdGroupe()    { return idGroupe; }
    public int    getIdEncadrant() { return idEncadrant; }

    // Setters
    public void setId(int id)               { this.id = id; }
    public void setStatut(String statut)    { this.statut = statut; }
}