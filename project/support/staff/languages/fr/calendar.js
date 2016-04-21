// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mishoo@infoiasi.ro>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("dimanche",
 "lundi",
 "mardi",
 "mercredi",
 "jeudi",
 "vendredi",
 "samedi",
 "dimanche");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sun",
 "Mon",
 "Tue",
 "Wed",
 "Thu",
 "Fri",
 "Sat",
 "Sun");

// full month names
Calendar._MN = new Array
("janvier",
 "f�vrier",
 "mars",
 "avril",
 "mai",
 "juin",
 "juillet",
 "ao�t",
 "septembre",
 "octobre",
 "novembre",
 "d�cembre");

// short month names
Calendar._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "May",
 "Jun",
 "Jul",
 "Aug",
 "Sep",
 "Oct",
 "Nov",
 "Dec");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "� propos du calendrier";

Calendar._TT["ABOUT"] =
"DHTML date de/temps S�lecteur\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Pour consulter toute derni�re version: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribu� sous GNU LGPL.  See http://gnu.org/licenses/lgpl.html pour plus de d�tails." +
"\n\n" +
"date de la s�lection:\n" +
"- Utilisez le \xab, \xbb boutons pour s�lectionner l'ann�e\n" +
"- Utilisez le " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pour selectionner les mois\n" +
"- Tenez le bouton de la souris sur un des boutons ci-dessus pour acc�l�rer la s�lection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"s�lection du temps:\n" +
"- Cliquez sur l'une des pi�ces du temps de l'augmenter\n" +
"- ou Shift-cliquez pour le diminuer\n" +
"- ou cliquez et glissez pour acc�l�rer la s�lection.";

Calendar._TT["PREV_YEAR"] = "Prev. year (maintenir pour menu)";
Calendar._TT["PREV_MONTH"] = "Prev. month (maintenir pour menu)";
Calendar._TT["GO_TODAY"] = "aller Aujourd'hui";
Calendar._TT["NEXT_MONTH"] = "Next month (maintenir pour menu)";
Calendar._TT["NEXT_YEAR"] = "Next year (maintenir pour menu)";
Calendar._TT["SEL_DATE"] = "S�lectionnez la date";
Calendar._TT["DRAG_TO_MOVE"] = "Faites glisser pour d�placer";
Calendar._TT["PART_TODAY"] = " (today)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Afficher le premier dimanche";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fermer";
Calendar._TT["TODAY"] = "aujourd'hui";
Calendar._TT["TIME_PART"] = "(Shift-)Cliquez ou faites glisser pour modifier la valeur";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "temps:";
