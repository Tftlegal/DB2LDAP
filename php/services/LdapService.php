<?php
require_once('./secrets/ldap.php');

enum GroupDNs: string
{
    case KULEI = 'cn=kulei,ou=groups,dc=junges-muensterschwarzach,dc=de';
    case KULEI_IT = 'cn=kulei-it,ou=groups,dc=junges-muensterschwarzach,dc=de';
    case KULEI_LEITUNG = 'cn=kulei-leitung,ou=groups,dc=junges-muensterschwarzach,dc=de';
    case KULEI_PR = 'cn=kulei-pr,ou=groups,dc=junges-muensterschwarzach,dc=de';
}

class LdapService
{

    public static function loadUser(int $uid): array
    {
        try {
            $ds = self::getDS();
            $search = ldap_search($ds, LDAP_BASE_USER, sprintf('uid=%d', $uid));
            return ldap_get_entries($ds, $search)[0] ?? [];
        } catch (Exception $exc) {
            throw new Exception("Benutzer konnte nicht gesucht werden.");
        } finally {
            ldap_close($ds);
        }
    }

    public static function saveUser(array $ldapUser): array
    {
        try {
            $ds = self::getDS();

            if (isset($ldapUser['dn']) === false) {
                $dn = sprintf(
                    'cn=%s,%s',
                    $ldapUser['uid'],
                    LDAP_BASE_USER
                );
                ldap_add($ds, $dn, $ldapUser);
            } else {
                $dn = $ldapUser['dn'];
                unset($ldapUser['dn']);
                ldap_modify($ds, $dn, $ldapUser);
            }
            $ldapUser['dn'] = $dn;

            $errorCode = ldap_errno($ds);
            if ($errorCode !== 0) {
                throw new Exception(ldap_err2str($errorCode));
            }

            return $ldapUser;
        } catch (Exception $exc) {
            throw new Exception(
                sprintf(
                    "Benutzer konnte nicht zum LDAP-Server synchronisiert werden: %s",
                    $exc->getMessage()
                )
            );
        }
    }

    public static function saveGroupMemberships(array $ldapUser, array $ldapGroupMemberships): void
    {
        try {
            $groupDNs = array_map(fn ($g) => $g->value, $ldapGroupMemberships);
            $ds = self::getDS();

            foreach (GroupDNs::cases() as $groupDN) {
                $search = ldap_search($ds, $groupDN->value, sprintf('member=%s', $ldapUser['dn']));
                $doesExist = ldap_count_entries($ds, $search) === 1;
                $shouldExist = in_array($groupDN->value, $groupDNs);

                if ($shouldExist !== $doesExist) {
                    if ($shouldExist === true) {
                        ldap_mod_add($ds, $groupDN->value, ['member' => $ldapUser['dn']]);
                    } else {
                        ldap_mod_del($ds, $groupDN->value, ['member' => $ldapUser['dn']]);
                    }

                    $errorCode = ldap_errno($ds);
                    if ($errorCode !== 0) {
                        throw new Exception(ldap_err2str($errorCode));
                    }
                }
            }
        } catch (Exception $exc) {
            throw new Exception("Gruppenmitgliedschaften konnte nicht zum LDAP-Server synchronisiert werden.");
        } finally {
            ldap_close($ds);
        }
    }

    private static function getDS(): LDAP\Connection
    {
        try {
            // chain cert must be set before attempting to connect, otherwise TLS handshake fails
            ldap_set_option(
                NULL,
                LDAP_OPT_X_TLS_CACERTFILE,
                sprintf(
                    '/var/data/certbot-conf/live/%s/chain.pem',
                    LDAP_SERVER
                )
            );
            ldap_set_option(NULL, LDAP_OPT_PROTOCOL_VERSION, 3);
            // connect
            $ds = ldap_connect(sprintf('ldaps://%s:%s', LDAP_SERVER, LDAP_PORT));
            ldap_bind($ds, LDAP_USERNAME, LDAP_PASSWORD);
            return $ds;
        } catch (Exception $exc) {
            throw new Exception("Keine Anmeldung beim LDAP-Server möglich.");
        }
    }
}
