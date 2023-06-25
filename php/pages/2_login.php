<?php

    class LoginPage extends Page {

        public static function getTitle(): string {
            return "Anmeldung";
        }

        public static function renderContent(): void {
?>
            <form method="post">
                <div class="row">
                    <label class="col-sm-3 col-form-label" for="eMailAddress">E-Mail-Adresse:</label>
                    <div class="col-sm-9">
                        <input id="eMailAddress" class="form-control" name="eMailAddress" type="eMailAddress"
                            value="<?php echo($_POST['eMailAddress'] ?? ''); ?>"
                        />
                        <small class="form-text text-muted">Die E-Mail-Adresse, mit der du dich in der App anmeldest.</small>
                    </div>
                </div>

                <div class="row mt-3">
                    <label class="col-sm-3 col-form-label" for="password">Passwort:</label>
                    <div class="col-sm-9">
                        <input id="password" class="form-control" name="password" type="password"/>
                        <small class="form-text text-muted">Falls du dein Passwort haben solltest, kannst du es
                            <a href="https://app.junges-muensterschwarzach.de/help/reset_password" target="_blank">hier</a> zurücksetzen.
                        </small>
                    </div>
                </div>
                <?php if (isset($_POST['error']) === true) { ?>
                    <div id="error" class="row mt-3">
                        <div class="col-sm-9 offset-sm-3">
                            <small class="form-text text-danger"><?php echo($_POST['error']); ?></small>
                        </div>
                    </div>
                <?php } ?>
                <input type="number" name="page" value="<?php echo(array_search(ControlPage::class, PAGES)); ?>" class="d-none"/>
                <input type="submit" id="submit" class="d-none"/>
            </form>
<?php            
        }

        public static function renderNavigation(): void {
            ?>
                <label for="submit" class="btn btn-success m-1">Anmelden <i class="bi bi-arrow-right"></i></label>
            <?php
        }

    }

?>