<?php

    class IntroductionPage extends Page {

        public static function getTitle(): string {
            return "Einführung";
        }

        public static function render(): void {
            $QAs = [
                new QA(
                    "Was ist DB<span class='text-danger'>2</span>LDAP?",
                    "Diese Website führt euch durch den Migrationsprozess, um eure Benutzerdaten von der Datenbank des App-Servers
                     zu unserem neuen Verzeichnisdienst zu migrieren.",
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
                     Alle Dienste können die Benutzerdaten aus einer '<strong>einzigen Quelle der Wahrheit</strong>' beziehen,
                     die wir zentral und datenschutzkonform auf unserem eigenen Server pflegen. Ein weiterer Vorteil ist,
                     dass der Login über dieselben zentral gespeicherten Logindaten bei allen Diensten möglich ist.
                    <img src='assets/transformation.png' class='w-100'></img>",
                    true
                ),
                new QA(
                    "Warum ist mein manuelles Handeln erforderlich?",
                    "Im nachfolgenden Schaubild",
                    true
                ),
                new QA(
                    "Muss ich diese Migration wiederholen?",
                    "Dies ist notwendig, weil ...",
                    true
                ),
                new QA(
                    "Müssen unsere Teilnehmer diese Migration auch durchführen?",
                    "Dies ist notwendig, weil ..."
                )
                ];
            ?>
            <h2></h2>
            <span></span>

            <h2></h2>
            <span></span>

            <h2></h2>
            <span></span>

            <div class="accordion" id="QAs">
                <?php for ($i = 0; $i < COUNT($QAs); ++$i) { ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-ci-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#QA<?php echo($i); ?>">
                                <?php echo($QAs[$i]->getQuestion()); ?>
                            </button>
                        </h2>
                        <div id="QA<?php echo($i); ?>" class="accordion-collapse collapse <?php if ($QAs[$i]->isOpen()) echo 'show'; ?>" data-bs-parent="#QAs">
                            <div class="accordion-body">
                                <?php echo($QAs[$i]->getAnswer()); ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
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