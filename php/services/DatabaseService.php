<?php
    require_once('/var/data/secrets/jmdb2ldap/db.php');

    enum AccessLevels: string {
        case account_accessLevel_developer = 'Entwickler';
        case account_accessLevel_courseLeader = 'Kursleitung';
        case account_accessLevel_courseInstructor = 'Kursleiter';
        case account_accessLevel_editor = 'Redakteur';
        case account_accessLevel_user = 'Benutzer';
        case account_accessLevel_guest = 'Gast';
    }
    
    enum EatingHabits: string {
        case GLUTENFREE = 'glutenfrei';
        case LACTOSEFREE = 'laktosefrei';
        case VEGAN = 'vegan';
        case VEGETARIAN = 'vegetarisch';
        case OMNIVOROUS = 'omnivor';
    }

    class DatabaseService {

        public static function getUser(string $eMailAddress, string $password): array {
            try {
                $mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

                $stmt = $mysql->prepare(
                    "SELECT ad.userId, ad.displayName, ad.firstName, ad.lastName, ad.eMailAddress, 
                    ad.streetName, ad.houseNumber, ad.supplementaryAddress, 
                    ad.zipCode, ad.city, ad.country, ad.phoneNumber, ad.birthdate, 
                    ad.eatingHabits, ad.allowPost, ad.allowNewsletter, ad.isActivated, 
                    ad.passwordHash, 
                    ad.registrationDate, ad.modificationDate, 
                    al.accessLevel, al.accessIdentifier 
                    FROM access_levels al, account_data ad 
                    WHERE al.accessLevel=ad.accessLevel AND ad.eMailAddress=?"
                );
                $stmt->bind_param("s", $eMailAddress);
                
                if ($stmt->execute() === false) {
                    throw new Exception(
                        "Unerwarteter Fehler! Bitte versuche es zu einem späteren Zeitpunkt erneut 
                        oder kontaktiere Lucas bei Bestehen des Problems!"
                    );
                }
                $user = $stmt->get_result()->fetch_assoc();

                if ($user === null || password_verify($password, $user["passwordHash"]) === false) {
                    throw new Exception("Anmeldung fehlgeschlagen!");
                }
                if (intval($user["isActivated"]) === 0) {
                    throw new Exception("Bitte aktiviere deinen Account vor der Migration!");
                }

                return $user;
            } catch (Exception $exc) {
                throw $exc;
            } finally {
                $mysql->close();
            }
        }

    }

?>