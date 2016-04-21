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
("Sonntag",
 "Montag",
 "Dienstag",
 "Mittwoch",
 "Donnerstag",
 "Freitag",
 "Samstag",
 "Sonntag");

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
("Januar",
 "Februar",
 "M�rz",
 "April",
 "Mai",
 "Juni",
 "Juli",
 "August",
 "September",
 "Oktober",
 "November",
 "Dezember");

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
Calendar._TT["INFO"] = "�ber den Kalender";

Calendar._TT["ABOUT"] =
"DHTML Datum / Zeit Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"F�r aktuelle Version besuchen: http://dynarch.com/mishoo/calendar.epl\n" +
"Lizensiert unter GNU LGPL.  sehen http://gnu.org/licenses/lgpl.html Details." +
"\n\n" +
"Datum Auswahl:\n" +
"- verwenden Sie die \xab, \xbb Tasten zu Jahr w�hlen\n" +
"- verwenden Sie die " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " Tasten Monat ausw�hlen\n" +
"- Halten Sie Maustaste auf ein beliebiges der oben genannten Schaltfl�chen zur schnelleren Auswahl.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Zeitvorwahl:\n" +
"- Klicken Sie auf einen der Zeit Teile um sie zu erh�hen\n" +
"- oder Shift-Klick um sie zu verringern\n" +
"- oder klicken und ziehen f�r eine schnellere Auswahl.";

Calendar._TT["PREV_YEAR"] = "Prev. year (halten f�r Men�)";
Calendar._TT["PREV_MONTH"] = "Prev. month (halten f�r Men�)";
Calendar._TT["GO_TODAY"] = "Go Heute";
Calendar._TT["NEXT_MONTH"] = "Next month (halten f�r Men�)";
Calendar._TT["NEXT_YEAR"] = "Next year (halten f�r Men�)";
Calendar._TT["SEL_DATE"] = "W�hlen Sie das Datum";
Calendar._TT["DRAG_TO_MOVE"] = "Ziehen Sie sich zu bewegen";
Calendar._TT["PART_TODAY"] = " (today)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Anzeige Sonntag Der erste";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "in der N�he";
Calendar._TT["TODAY"] = "heute";
Calendar._TT["TIME_PART"] = "(Shift-)Klicken oder ziehen Sie den Wert zu �ndern";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "wk";
Calendar._TT["TIME"] = "Zeit:";
