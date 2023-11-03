Weiterentwicklung von Stud.IP und Integration in Courseware.

Die Abnahme erfolgt spätestens am 12.12.2023 auf Basis der Entwicklungsleistung und ist unabhängig vom Zurückspielen des Codes in den Stud.IP Kern, da dies außerhalb des Einflusses des Auftragsnehmers liegt.


Allgemein

In der Stud.IP Courseware besteht die Möglichkeit, Lernkarten zu hinterlegen und zum Lernen zu verwenden. Jedoch ist der Funktionsumfang so gering, dass die Courseware-Lernkarten nicht sinnvoll zum Einsatz kommen. Andere kommerzielle Anbieter (Repetico, …) bieten viele weitere Funktionen, die von den Lehrenden und Studierenden als unentbehrlich empfunden werden.


Generelles

- Die Verwaltung und Benutzung der Lernkarten muss in einem eigenen Reiter sowohl im Kontext der Benutzenden (Arbeitsplatz) als auch im Veranstaltungskontext verfügbar sein. Die Benutzung ist zusätzlich in der Courseware möglich.

- Eine Implementation mit Vue.js ist notwendig, um ein vergleichbares Nutzungserlebnis wie z.B. Repetico bieten zu können.

- Das Design ist ansprechend.

- Kartendecks sollen weiterhin als Block in Courseware eingebunden werden können (ersetzt den alten Block)

- Die Implementation muss möglichst barrierearm sein.

- Die Implementation erfolgt als Stud.IP Plugin für den Stud.IP-Custombuild der Universität Osnabrück (Version 5.3)

- Das Plugin soll für eine einfache Integration in den Stud.IP Core vorbereitet sein


Lernkarten

- Lernkarten müssen auf Vorder- und Rückseite Bilder und formatierte Texte (WYSIWYG) enthalten können. (Schriftgröße, Strichstärke, Kursivschrift, Aufzählung, …)

- Für jede Lernkarte wird für Lernende hinterlegt, ob und wie häufig eine Karte als „gewusst“, „teilweise gewusst“ oder „nicht gewusst“ gelernt wurden.

- Für Karten, die aus einer Kopie einer Kartendeck-Vorlage stammen, wird festgehalten, wenn die Karte abweichend von der Vorlage bearbeitet wurde

- Prio gering: Wenn ich die Katen kopiert und danach angepasst habe, kann ich die Anpassungen wieder auf den Ursprungsstand zurücksetzen

- Prio gering: Karten erlauben die Einbindung von Audiodateien (Audioblock sollte im gleichen Zuge optisch renoviert werden und genauso aussehen – bessere Orientierung).


Kartendecks

- Kartensätze müssen von Lehrenden und Lernenden angelegt und angepasst (Bearbeitungsmodus), gelesen (Lesemodus) und gelernt (Lernmodus) werden können. Ein Kartendeck gehört den Erstellenden (Wenn man eine Kopie bekommt, ist man Erstellende:r)

- Ich habe die Möglichkeit, mit dem Original zu lernen. Das bedeutet, Änderungen (von den erstellenden Personen) an den Karten werden übernommen. Der Lernstand der betreffenden Karte wird dann für alle Lernenden zurückgesetzt und die Karte ist markiert. Wenn ich mit dem Original lerne, dann kann ich keine Veränderungen am Kartensatz vornehmen. Wird ein Kartensatz z.B. nur in die CW einer Veranstaltung eingebunden, lerne ich am Original.

- Erstellende können in Veranstaltung und Arbeitsplatz Kopien der Lernkarten für andere Nutzer:innen bereitstellen. Wenn ich mir eine „Kopie nehme“, erscheint diese im eigenen Arbeitsplatz. Dort ist sie bearbeitbar.

- Im Lernkartenreiter im Arbeitsplatz wird deutlich, welche Kartensätze „Kopien“ und welche „Originale“ sind

Es gibt eine Fortschrittsanzeige an den Kartensätzen die anzeigt, wie viel % der Karten gewusst“, „teilweise gewusst“ oder „nicht gewusst“ wurden und wann zuletzt gelernt wurde.

- Kartendecks müssen in der Oberfläche in einer verschachtelten Verzeichnisstruktur ähnlich einem Dateisystem hierarchisch abgelegt werden können. (Säugetiere – Große Säugetiere – Elefanten – Blaue Elefanten)

- Kartendecks müssen exportiert und importiert werden können. Der Export muss so möglich sein, dass der Import auf anderen Plattformen möglich ist (insbesondere Anki, Repetico).

- Benutzende können ihre Kartendecks als Vorlage mit anderen Personen, Gruppen in Veranstaltungen oder allen Personen einer Veranstaltung teilen.

- Aus der Vorlage kann dann eine persönliche Kopie erstellt werden. Eine weitere Bearbeitung wirkt sich nicht auf die Ursprungsvorlage aus.

- Den Erstellenden einer Vorlage wird die Anzahl der davon abgeleiteten Kopien angezeigt.

- Metadaten für Karten mit u.a. Copyright Informationen. Auch hier wären optionale CC Lizenzen sicherlich wünschenswert.


Bearbeitungsmodus

- Benutzende können Kartendecks erstellen, bearbeiten und löschen.

- Die UX für die Erstellung und Bearbeitung der Kartendecks lehnt sich an Repetico an.


Lesemodus

- Benutzende können ein oder mehrere Kartendecks lesen und so auswendig lernen. Die Karten der Decks werden ihnen dazu in fester oder zufälliger Reihenfolge „aufgedeckt“ präsentiert. Dabei wird noch kein Lernfortschritt festgehalten.


Lernmodus

- Benutzende können einzelne oder mehrere Kartendecks lernen. Die Karten der Decks werden ihnen dazu in einer bestimmten Reihenfolge „aufgedeckt“ (jede einzelne Karte - Vorderseite) präsentiert.

- Die Reihenfolge der Anzeige der Lernkarten kann eingestellt werden. Sie können in fester Reihenfolge, in zufälliger Reihenfolge oder in der Reihenfolge des niedrigsten Wissensstandes angezeigt werden.

- Zunächst wird für jede Lernkarte nur die Vorderseite angezeigt. Außerdem gibt es ein Freitextfeld, in welches die Lernenden ihre Antwort eintippen können. Nach Bestätigung wird nun die Rückseite der Lernkarte zusammen mit der Antwort der Lernenden angezeigt. Die Lernenden schätzen nun selbst den persönlichen Wissensstand als „gewusst“, „teilweise gewusst“ oder „nicht gewusst“ ein.

- Nach Beendigung des Lernmodus wird eine Statistik zum Lernstand der gelernten Kartendecks angezeigt.

- Diese Einschätzungen werden bei der Ermittlung der Reihenfolge nach niedrigstem Wissensstand berücksichtigt. Dabei soll der SM-2 Algorithmus verwendet werden.

- Lernstatistik verändert sich zu „irgendwas Schönem“, wenn der Fortschritt bei 100% ist (im Arbeitsplatz einstellbar)


Einbindung in Courseware

- Courseware-Autor:innen können Kartensätze in ein Courseware-Lernmaterial einbinden (Arbeitsplatz oder Veranstaltung).

- Die Lernkarten werden als Block eingebunden.

- Courseware-Autor:innen können Kartensätze aus der Veranstaltung und dem eigenen Arbeitsplatz einbinden.

- Die Kartensätze können von Nutzer:innen in der CW beantwortet werden

- Die bereitgestellten Kartensätze finden sich im Arbeitsplatz der Nutzer:innen wieder (gekennzeichnet aus welcher Veranstaltung sie kommen)

- Egal wo ich den Kartensatz beantworte, der Fortschritt wird „global“ im Arbeitsplatz-Reiter erfasst
