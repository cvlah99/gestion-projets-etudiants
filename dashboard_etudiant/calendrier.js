/* =============================================
   CALENDRIER — calendrier.js
   Affiche le mois courant dans le dashboard
============================================= */

// Récupérer les zones HTML
var zoneMonth = document.getElementById("cal-month");
var zoneGrid  = document.getElementById("cal-grid");

// Date actuelle
var maintenant  = new Date();
var annee       = maintenant.getFullYear();
var moisActuel  = maintenant.getMonth();    // 0 = Janvier ... 11 = Décembre
var aujourdhui  = maintenant.getDate();

// Noms des mois en français
var nomsMois = [
  "Janvier", "Février", "Mars", "Avril",
  "Mai", "Juin", "Juillet", "Août",
  "Septembre", "Octobre", "Novembre", "Décembre"
];

// Afficher "Mai 2026" par exemple
zoneMonth.textContent = nomsMois[moisActuel] + " " + annee;

// Noms des jours
var nomsJours = ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"];

// Quel jour de la semaine est le 1er du mois ?
var premierJour = new Date(annee, moisActuel, 1).getDay();

// Combien de jours dans ce mois ?
var nbJours = new Date(annee, moisActuel + 1, 0).getDate();


// --- Ajouter les en-têtes (Di, Lu, Ma...) ---
for (var i = 0; i < nomsJours.length; i++) {
  var label = document.createElement("div");
  label.className   = "cal-label";
  label.textContent = nomsJours[i];
  zoneGrid.appendChild(label);
}

// --- Cases vides avant le 1er jour ---
for (var i = 0; i < premierJour; i++) {
  var vide = document.createElement("div");
  vide.className = "cal-case";
  zoneGrid.appendChild(vide);
}

// --- Jours du mois ---
for (var jour = 1; jour <= nbJours; jour++) {
  var caseJour = document.createElement("div");
  caseJour.className   = "cal-case";
  caseJour.textContent = jour;

  // Surligner le jour d'aujourd'hui
  if (jour === aujourdhui) {
    caseJour.className += " cal-today";
  }

  zoneGrid.appendChild(caseJour);
}
