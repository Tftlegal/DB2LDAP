<?php
    require('./php/services/DatabaseService.php');

    class ControlPage extends Page {

        public static function getTitle(): string {
            return "Kontrolle";
        }

        public static function preprocess(): void {
            try {
                $_POST = array_merge($_POST, DatabaseService::loadUser($_POST['eMailAddress'] ?? '', $_POST['password'] ?? ''));
                $_POST['state'] = self::getState();
                $_POST['eatingHabit'] = self::getEatingHabit();
            } catch (Exception $exc) {
                $_POST['page'] = array_search(LoginPage::class, PAGES);
                $_POST['error'] = 'Fehler: ' . $exc->getMessage();
            }
        }

        public static function renderContent(): void {
            ?>
                <form method="post">
                    
                    <h1 class="fs-4">Accountdaten</h1>

                    <div class="row">
                        <label class="col-sm-3 col-form-label" for="userId">Benutzer-Id:</label>
                        <div class="col-sm-9">
                            <input id="userId" class="form-control" name="userId" type="number"
                                value="<?php echo($_POST['userId']); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="accessIdentifier">Berechtigungsstufe:</label>
                        <div class="col-sm-9">
                            <select id="accessIdentifier" class="form-select" name="accessIdentifier" disabled>
                                <?php foreach (AccessLevels::cases() as $level) { ?>
                                    <option value="<?php echo($level->name); ?>"
                                            <?php if ($_POST['accessIdentifier'] === $level->name) echo("selected"); ?>>
                                        <?php echo($level->value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="eMailAddress1">E-Mail-Adresse:</label>
                        <div class="col-sm-9">
                            <input id="eMailAddress1" class="form-control" name="eMailAddress" type="eMailAddress"
                                value="<?php echo($_POST['eMailAddress']); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="displayName">Anzeigename:</label>
                        <div class="col-sm-9">
                            <input id="displayName" class="form-control" name="displayName"
                                value="<?php echo($_POST['displayName']); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="registrationDate">Datum der Registrierung:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input id="registrationDate" class="form-control" name="registrationDate" type="datetime-local"
                                    value="<?php echo($_POST['registrationDate']); ?>" disabled/>
                                <span class="input-group-text">Uhr</span>
                            </div>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="modificationDate">Datum der letzten Änderung:</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input id="modificationDate" class="form-control" name="modificationDate"
                                    type="<?php echo(isset($_POST['modificationDate']) ? 'datetime-local' : 'text'); ?>"
                                    value="<?php echo($_POST['modificationDate'] ?? '-'); ?>" disabled/>
                                <span class="input-group-text">Uhr</span>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="fs-4 mt-4">Kontaktdaten</h1>

                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="firstName">Vorname:</label>
                        <div class="col-sm-9">
                            <input id="firstName" class="form-control" name="firstName"
                                value="<?php echo($_POST['firstName'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="lastName">Nachname:</label>
                        <div class="col-sm-9">
                            <input id="lastName" class="form-control" name="lastName"
                                value="<?php echo($_POST['lastName'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="streetName">Straße:</label>
                        <div class="col-sm-9">
                            <input id="streetName" class="form-control" name="streetName"
                                value="<?php echo($_POST['streetName'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="houseNumber">Hausnummer:</label>
                        <div class="col-sm-9">
                            <input id="houseNumber" class="form-control" name="houseNumber"
                                value="<?php echo($_POST['houseNumber'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="supplementaryAddress">Adresszusatz:</label>
                        <div class="col-sm-9">
                            <input id="supplementaryAddress" class="form-control" name="supplementaryAddress"
                                value="<?php echo($_POST['supplementaryAddress'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="zipCode">Postleitzahl:</label>
                        <div class="col-sm-9">
                            <input id="zipCode" class="form-control" name="zipCode"
                                value="<?php echo($_POST['zipCode'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="city">Stadt:</label>
                        <div class="col-sm-9">
                            <input id="city" class="form-control" name="city"
                                value="<?php echo($_POST['city'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="country">Staat:</label>
                        <div class="col-sm-9">
                            <input id="country" class="form-control" name="country"
                                value="<?php echo($_POST['country'] ?? ''); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="allowPost">Posterhalt:</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <input id="allowPost" class="form-check-input m-0" name="allowPost" type="checkbox"
                                <?php if ($_POST['allowPost'] == 1) echo("checked"); ?> disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="allowNewsletter">Newslettererhalt:</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <input id="allowNewsletter" class="form-check-input m-0" name="allowNewsletter" type="checkbox"
                                <?php if ($_POST['allowNewsletter'] == 1) echo("checked"); ?> disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="eMailAddress2">E-Mail-Adresse:</label>
                        <div class="col-sm-9">
                            <input id="eMailAddress2" class="form-control" name="eMailAddress" type="eMailAddress"
                                value="<?php echo($_POST['eMailAddress']); ?>" disabled/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="phoneNumber">Telefonnummer:</label>
                        <div class="col-sm-9">
                            <input id="phoneNumber" class="form-control" name="phoneNumber"
                                value="<?php echo($_POST['phoneNumber'] ?? ''); ?>" disabled/>
                        </div>
                    </div>

                    <h1 class="fs-4 mt-4">Veranstaltungsrelevante Daten</h1>

                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="birthdate">Geburtsdatum:</label>
                        <div class="col-sm-9">
                            <input id="birthdate" class="form-control" name="birthdate" type="date"
                                value="<?php echo(strstr($_POST['birthdate'] ?? '', ' ', true)); ?>" disabled/>
                        </div>
                    </div>

                    <h1 class="fs-4 mt-4 text-danger">Zu überprüfende Daten</h1>

                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="state">Bundesland:</label>
                        <div class="col-sm-9">
                            <input id="state" class="form-control" name="state"
                                value="<?php echo($_POST['state'] ?? ''); ?>"/>
                        </div>
                    </div>
                    <div class="row my-2">
                        <label class="col-sm-3 col-form-label" for="eatingHabit">Ernährungsweise:</label>
                        <div class="col-sm-9">
                            <select id="eatingHabit" class="form-select" name="eatingHabit">
                                <?php foreach (EatingHabits::cases() as $habit) { ?>
                                    <option value="<?php echo($habit->name); ?>"
                                            <?php if ($_POST['eatingHabit'] === $habit->name) echo("selected"); ?>>
                                        <?php echo($habit->value); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-9 offset-sm-3">
                            <small class="form-text text-warning">
                                Hinweis: Sollten Werte von ausgegrauten Attributen inkorrekt sein,
                                dann sollten diese vor der Migration über das
                                <a href="https://app.junges-muensterschwarzach.de/profile" target="_blank">Profil</a>
                                in der App korrigiert werden.
                            </small>
                        </div>
                    </div>
                    
                    <!-- error -->
                    <?php if (isset($_POST['error']) === true) { ?>
                        <div class="row mt-3">
                            <div class="col-sm-9 offset-sm-3">
                                <small class="form-text text-danger"><?php echo($_POST['error']); ?></small>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- hidden -->
                    <input type="password" name="password" value="<?php echo($_POST['password']); ?>" class="d-none"/>
                    <input type="number" id="page" name="page" value="<?php echo(array_search(ConclusionPage::class, PAGES)); ?>" class="d-none"/>
                    <input type="submit" id="submit" class="d-none"/>
                </form>
            <?php
        }

        public static function renderNavigation(): void {
            ?>
                <label for="submit" class="btn btn-secondary m-1"
                    onclick="
                        document.getElementById('page').value=<?php echo(array_search(ControlPage::class, PAGES)); ?>;
                    ">
                    Aktualisieren <i class="bi bi-arrow-clockwise"></i>
                </label>
                <label for="submit" class="btn btn-success m-1">Nächste Seite <i class="bi bi-arrow-right"></i></label>
            <?php
        }

        private static function getState(): string {
            $state = '';

            if (isset($_POST['zipCode']) && isset($_POST['country'])) {
                switch ($_POST['country']) {
                    case preg_match("/^(österreich|austria|at)$/i", $_POST['country']):
                        $cc = 'at';
                        break;
                    case preg_match("/^(schweiz|switzerland|ch)$/i", $_POST['country']):
                        $cc = 'ch';
                        break;
                    default:
                        $cc = 'de';
                }
                $state = json_decode(
                    file_get_contents(sprintf('https://api.zippopotam.us/%s/%s', $cc, $_POST['zipCode'])),
                    true
                )['places'][0]['state'] ?? '';
            }

            return $state;
        }

        private static function getEatingHabit(): string {
            switch ($_POST['eatingHabits'] ?? '') {
                case boolval(preg_match("/(?<!nicht\s)gluten(-|\s)?frei/i", $_POST['eatingHabits'] ?? '')):
                    $eatingHabit = EatingHabits::GLUTENFREE;
                    break;
                case boolval(preg_match("/(?<!nicht\s)la(c|k)tose(-|\s)?frei/i", $_POST['eatingHabits'] ?? '')):
                    $eatingHabit = EatingHabits::LACTOSEFREE;
                    break;
                case boolval(preg_match("/(?<!nicht\s)(vegan|pflanzlich|herbivor)/i", $_POST['eatingHabits'] ?? '')):
                    $eatingHabit = EatingHabits::VEGAN;
                    break;
                case boolval(preg_match("/((?<!nicht\s)(vegetar|vegg?(ie?|y)|pes(c|k)etar)|(?<=kein)\s*(fleisch|fisch)
                        |(fleisch|fisch)\s*(?=nicht))/i", $_POST['eatingHabits'] ?? '')):
                    $eatingHabit = EatingHabits::VEGETARIAN;
                    break;
                default:
                    $eatingHabit = EatingHabits::OMNIVOROUS;
                    break;
            }

            return $eatingHabit->name;
        }

    }

?>