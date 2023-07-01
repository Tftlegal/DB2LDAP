<?php
require_once('./php/services/DatabaseService.php');
require_once('./php/services/LdapService.php');

class ConclusionPage extends Page
{

    public static function getTitle(): string
    {
        return "Abschluss";
    }

    public static function preprocess(): void
    {
        try {
            // only works as long as modified attributes do not exist in the database schema yet, otherwise merge attributes manually
            $user = array_merge($_POST, DatabaseService::getUser($_POST['eMailAddress'], $_POST['password']));

            $ldapUser = LdapService::loadUser($user['userId']);

            // migrate user
            $ldapUser = self::updateLdapUser($ldapUser, $user);
            $ldapUser = LdapService::saveUser($ldapUser);

            // migrate groups
            $ldapGroupMemberships = self::getGroupMemberships($user);
            LdapService::saveGroupMemberships($ldapUser, $ldapGroupMemberships);
        } catch (Exception $exc) {
            $_POST['page'] = array_search(ControlPage::class, PAGES);
            $_POST['error'] = 'Fehler: ' . $exc->getMessage();
        }
    }

    private static function updateLdapUser(array $ldapUser, array $user): array
    {
        // dn
        if (isset($ldapUser['dn'])) {
            $ldapUser = [
                'dn' => $ldapUser['dn']
            ];
        }

        // objectClass
        $ldapUser['objectClass'] = [
            'jmPerson',
            'top'
        ];

        // user attribute mappings
        $mapping = [
            'jmCountry' => 'country',
            'cn' => 'userId',
            'displayName' => 'displayName',
            'givenName' => 'firstName',
            'jmAllowNewsletter' => [
                'allowNewsletter',
                fn ($v) => boolval($v) ? 'TRUE' : 'FALSE'
            ],
            'jmAllowPost' => [
                'allowPost',
                fn ($v) => boolval($v) ? 'TRUE' : 'FALSE'
            ],
            'jmBirthdate' => [
                'birthdate',
                fn ($v) => sprintf(
                    '%s00Z',
                    str_replace('-', '', strstr($v, ' ', true))
                )
            ],
            'jmEatingHabit' => 'eatingHabit',
            'jmHouseNumber' => 'houseNumber',
            'jmIsActivated' => [
                'isActivated',
                fn ($v) => boolval($v) ? 'TRUE' : 'FALSE'
            ],
            'jmModificationDate' => [
                'modificationDate',
                fn ($v) => sprintf(
                    '%sZ',
                    str_replace(['-', ' ', ':'], '', $v)
                )
            ],
            'jmRegistrationDate' => [
                'registrationDate',
                fn ($v) => sprintf(
                    '%sZ',
                    str_replace(['-', ' ', ':'], '', $v)
                )
            ],
            'jmSupplementaryAddress' => 'supplementaryAddress',
            'l' => 'city',
            'mail' => 'eMailAddress',
            'postalCode' => 'zipCode',
            'sn' => 'lastName',
            'st' => 'state',
            'street' => 'streetName',
            'telephoneNumber' => 'phoneNumber',
            'title' => [
                'displayName',
                function ($v) {
                    switch ($v) {
                        case preg_match("/^br\./i", $v):
                            return "Bruder";
                        case preg_match("/^pt\./i", $v):
                            return "Pater";
                        case preg_match("/^sr\./i", $v):
                            return "Schwester";
                        default:
                            return null;
                    }
                }
            ],
            'uid' => 'userId',
            'userPassword' => [
                'password',
                function ($v) {
                    $salt = substr(
                        str_shuffle(
                            str_repeat(
                                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
                                4
                            )
                        ),
                        0,
                        4
                    );
                    return '{SSHA}' . base64_encode(
                        sha1($v.$salt, TRUE) . $salt
                    );
                }
            ]
        ];

        // apply mappings
        foreach ($mapping as $ldapKey => $dbSpec) {
            $dbKey = is_array($dbSpec) ? $dbSpec[0] : $dbSpec;
            $dbFn = is_array($dbSpec) ? $dbSpec[1] : fn ($x) => $x;

            if (!isset($user[$dbKey])) {
                unset($ldapUser[$ldapKey]);
                continue;
            }

            $value = $dbFn($user[$dbKey]);

            if (is_null($value)) {
                unset($ldapUser[$ldapKey]);
                continue;
            }

            $ldapUser[$ldapKey] = $value;
        }

        return $ldapUser;
    }

    private static function getGroupMemberships(array $user): array
    {
        $groupMemberships = [];

        // kulei
        if ($user['accessLevel'] >= 30) {
            $groupMemberships[] = GroupDNs::KULEI;
        }

        // kulei-it
        if ($user['accessLevel'] === 50) {
            $groupMemberships[] = GroupDNs::KULEI_IT;
        }

        // kulei-leitung
        if ($user['accessLevel'] === 40) {
            $groupMemberships[] = GroupDNs::KULEI_LEITUNG;
        }

        // kulei-pr
        if ($user['accessLevel'] >= 20 && $user['accessLevel'] !== 30) {
            $groupMemberships[] = GroupDNs::KULEI_PR;
        }

        return $groupMemberships;
    }

    public static function renderContent(): void
    {
?>
        <h2>Migration erfolgreich</h2>
        <span class="text-success">Deine Benutzerdaten wurden erfolgreich von der App-Datenbank zum LDAP-Server synchronisiert.</span>
    <?php
    }

    public static function renderNavigation(): void
    {
    ?>
        <form method="post" class="d-none">
            <input type="number" name="page" value="<?php echo (array_search(IntroductionPage::class, PAGES)); ?>" />
            <input type="submit" id="submit" />
        </form>

        <label for="submit" class="btn btn-secondary m-1"><i class="bi bi-skip-start-fill"></i> Zurück zur Startseite</label>
<?php
    }
}

?>