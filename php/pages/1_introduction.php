<?php

    class IntroductionPage extends Page {

        public static function getTitle(): string {
            return "Einführung";
        }

        public static function renderContent(): void {
            $QAs = [
                new QA(
                    "Was ist DB<span class='text-danger'>2</span>LDAP?",
                    "Diese Website führt euch durch den Migrationsprozess, um eure <strong>Benutzerdaten von der Datenbank des App-Servers
                     zu unserem neuen Verzeichnisdienst</strong> zu migrieren.",
                     true
                ),
                new QA(
                    "Warum werden die Benutzerdaten migriert?",
                    "Im nachfolgenden Schaubild ist auf der <strong>linken Seite der aktuelle Zustand unserer IT-Landschaft</strong> abgebildet:
                     Alle IT-Dienste sind voneinander getrennt und verwenden Benutzerdaten aus ihren eigenen Datenbanken.
                     Dadurch ist <strong>keine zentrale Verwaltung der Benutzerdaten</strong> und somit auch kein Login mit denselben Logindaten
                     bei verschiedenen Diensten möglich (es sei denn, man nutzt bei den verschiedenen Diensten dasselbe Passwort,
                     was man nicht tun sollte).<br/><br/>
                     Die <strong>rechte Seite zeigt den zukünftigen Aufbau unserer IT-Landschaft</strong>:
                     Alle Dienste können die Benutzerdaten aus einer '<strong>einzigen Quelle der Wahrheit</strong>' (= 'zentraler
                     Verzeichnisdienst' = '(Open)LDAP-Server') beziehen, die wir zentral und weiterhin datenschutzkonform auf unserem 
                     eigenen Server pflegen. Dies ermöglicht außerdem die zentrale Pflege von Zugriffsberechtigungen, Gruppenverwaltung
                     sowie den Login über dieselben zentral gespeicherten Logindaten bei allen Diensten.
                    <img src='assets/transformation.png' class='w-100'></img>"
                ),
                new QA(
                    "Warum ist mein manuelles Handeln erforderlich?",
                    "Die meisten Daten können automatisch migriert werden. Es gibt jedoch drei Attribute, die manuelles Handeln erfordern:
                     <ul>
                         <li><strong>Passwort</strong>: Der LDAP-Server nutzt zur Passwortverschlüsselung einen anderen Algorithmus
                             als die App-Datenbank. Da in der App-Datenbank die Passwörter nur verschlüsselt vorliegen und aus
                             Sicherheitsgründen die Passwörter nicht wieder entschlüsselt werden können,
                             muss das Klartext-Passwort zur Migration eingegeben werden, damit das Passwort vom LDAP-Server neu
                             verschlüsselt werden kann. Dies passiert beim Anmelden.</li>
                         <li><strong>Bundesland</strong>: Bisher haben wir das Bundesland nicht gespeichert, aber wir werden dies zukünftig
                             der Vollständigkeit halber tun. Das Bundesland wird automatisch über die bereits hinterlegte Postleitzahl
                             ermittelt, aber sollte nochmal überprüft werden.</li>
                         <li><strong>Ernährungsweise</strong>: Dieses Attribut war bisher Freitext. Da wir unsere Mahlzeiten allerdings
                             über die Klosterküche beziehen und dort die üblichen Verköstigungsformen angeboten werden (z.B. Vollkost,
                             vegetarisch usw.), wird dieses Feld zu einer beschränkten Auswahl umgewandelt. Da die Umwandlung aufgrund
                             des bisherigen Freitextes ein wenig fehleranfällig ist, bedarf es manueller Überprüfung.</li>
                     </ul>"
                ),
                new QA(
                    "Muss ich diese Migration wiederholen?",
                    "Njain. Bis ich irgendwann die neue App veröffentliche, wird es einen gewissen Parallelbetrieb geben.
                     Die angebundenen Dienste werden bereits die Benutzerdaten vom LDAP-Server verwenden, aber die aktuelle App-Version
                     verwendet weiterhin die Benutzerdaten aus der App-Datenbank. Solange du an den Benutzerdaten nichts änderst, brauchst du
                     die Migration nicht erneut durchführen, weil sich der Datenbestand dann nicht ändert. Solltest du in dieser
                     Übergangszeit allerdings deine <strong>Daten über die App ändern (z.B. neues Passwort, neue Adresse o.Ä.), dann musst
                     du diese Migration wiederholen</strong>, damit die Daten erneut von der App-Datenbank zum LDAP-Server synchronisiert werden."                ),
                new QA(
                    "Müssen unsere Teilnehmer diese Migration auch durchführen?",
                    "In gewisser Weise ja, allerdings nicht so explizit. Die Teilnehmer benötigen (zumindest zum aktuellen Zeitpunkt) keinen
                     Zugriff auf unsere angebundenen Dienste, sodass die Migration der Teilnehmerdaten erst erfolgt, wenn die neue App
                     veröffentlicht wird. Ich werde den Migrationsprozess dieser Website dann allerdings in das Anmeldesystem der neuen
                     App einbauen, sodass die Migration dann einmal pro Account <strong>beim ersten Login nach der Veröffentlichung
                     der neuen App</strong> durchgeführt werden muss. Eine erneute Migration ist dann nicht mehr notwendig."
                )
                ];
            ?>

            <div class="accordion" id="QAs">
                <?php for ($i = 0; $i < COUNT($QAs); ++$i) { ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-ci-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#QA<?php echo($i); ?>">
                                <?php echo($QAs[$i]->getQuestion()); ?>
                            </button>
                        </h2>
                        <div id="QA<?php echo($i); ?>" class="accordion-collapse collapse <?php if ($QAs[$i]->isOpen()) echo('show'); ?>" data-bs-parent="#QAs">
                            <div class="accordion-body">
                                <?php echo($QAs[$i]->getAnswer()); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public static function renderNavigation(): void {
            ?>
                <form method="post" class="d-none">
                    <input type="number" name="page" value="<?php echo(array_search(LoginPage::class, PAGES)); ?>"/>
                    <input type="submit" id="submit"/>
                </form>

                <label for="submit" class="btn btn-success m-1">Migration starten <i class="bi bi-arrow-right"></i></label>
            <?php
        }
    }

    class QA {

        function __construct(private string $question, private string $answer, private bool $isOpen = false) {
        }

        public function getQuestion(): string {
            return $this->question;
        }

        public function getAnswer(): string {
            return $this->answer;
        }

        public function isOpen(): bool {
            return $this->isOpen;
        }

    }

?>