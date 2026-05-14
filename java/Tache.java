// ============================================================
// Tache.java
// This class represents a task (tâche) assigned by an encadrant
// to a student (étudiant).
//
// This is the Java equivalent of the C++ Encadrant class
// (encadrant.cpp / encadrant.h) — same idea, same style.
// ============================================================

public class Tache {

    // --- Private fields ---
    // These store the task information
    private String titre;          // title of the task
    private String description;    // description / instructions
    private String dateLimite;     // deadline date (as a string, e.g. "2025-06-01")
    private int    idGroupe;       // the group this task is assigned to
    private int    idEncadrant;    // the encadrant who created the task

    // ----------------------------------------
    // Default constructor — creates empty task
    public Tache() {
        this.titre       = "";
        this.description = "";
        this.dateLimite  = "";
        this.idGroupe    = 0;
        this.idEncadrant = 0;
    }

    // ----------------------------------------
    // Constructor with all fields
    
    public Tache(String titre, String description, String dateLimite, int idGroupe, int idEncadrant) {
        this.titre       = titre;
        this.description = description;
        this.dateLimite  = dateLimite;
        this.idGroupe    = idGroupe;
        this.idEncadrant = idEncadrant;
    }
    // Getters
    public String getTitre(){
        return titre; }
    public String getDescription(){
     return description; }
    public String getDateLimite(){
      return dateLimite;}
    public int    getIdGroupe(){
         return idGroupe;    }
    public int    getIdEncadrant(){
     return idEncadrant; }

    
    // Setters 
    public void setTitre(String titre){
         this.titre = titre; }
    public void setDescription(String description){
      this.description = description; }
    public void setDateLimite(String dateLimite){
       this.dateLimite  = dateLimite;}
    public void setIdGroupe(int idGroupe){
              this.idGroupe    = idGroupe;}
    public void setIdEncadrant(int idEncadrant){
         this.idEncadrant = idEncadrant; }

}
