<?php

    class ConclusionPage extends Page {

        public static function getTitle(): string {
            return "Abschluss";
        }

        public static function renderContent(): void {
            
        }

        public static function renderNavigation(): void {
            ?>
                <form method="post" class="d-none">
                    <input id="page">2</input>
                    <input type="submit" id="submit"/>
                </form>

                <label for="submit" class="btn btn-success m-1">Nächste Seite <i class="bi bi-arrow-right"></i></label>
            <?php
        }

    }

?>