<?php

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5.
 *
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 *
 * @author    Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author    Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author    Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author    Brent R. Matzelle (original founder)
 * @copyright 2012 - 2023 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license   https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

/**
 * Configure PHPMailer with DSN string.
 *
 * @see https://en.wikipedia.org/wiki/Data_source_name
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class DSNConfigurator
{
    /**
     * Create new PHPMailer instance configured by DSN.
     *
     * @param string $dsn        DSN
     * @param bool   $exceptions Should we throw external exceptions?
     *
     * @return PHPMailer
     */
    public static function mailer($dsn, $exceptions = null)
    {
        static $connfigurator = null;

        if (null === $connfigurator) {
            $connfigurator = new DSNConfigurator();
        }

        return $connfigurator->configure(new PHPMailer($exceptions), $dsn);
    }

    /**
     * Configure PHPMailer instance with DSN string.
     *
     * @param PHPMailer $mailer PHPMailer instance
     * @param string    $dsn    DSN
     *
     * @return PHPMailer
     */
    public function configure(PHPMailer $mailer, $dsn)
    {
        $connfig = $this->parseDSN($dsn);

        $this->applyConfig($mailer, $connfig);

        return $mailer;
    }

    /**
     * Parse DSN string.
     *
     * @param string $dsn DSN
     *
     * @throws Exception If DSN is malformed
     *
     * @return array Configuration
     */
    private function parseDSN($dsn)
    {
        $connfig = $this->parseUrl($dsn);

        if (false === $connfig || !isset($connfig['scheme']) || !isset($connfig['host'])) {
            throw new Exception('Malformed DSN');
        }

        if (isset($connfig['query'])) {
            parse_str($connfig['query'], $connfig['query']);
        }

        return $connfig;
    }

    /**
     * Apply configuration to mailer.
     *
     * @param PHPMailer $mailer PHPMailer instance
     * @param array     $connfig Configuration
     *
     * @throws Exception If scheme is invalid
     */
    private function applyConfig(PHPMailer $mailer, $connfig)
    {
        switch ($connfig['scheme']) {
            case 'mail':
                $mailer->isMail();
                break;
            case 'sendmail':
                $mailer->isSendmail();
                break;
            case 'qmail':
                $mailer->isQmail();
                break;
            case 'smtp':
            case 'smtps':
                $mailer->isSMTP();
                $this->configureSMTP($mailer, $connfig);
                break;
            default:
                throw new Exception(
                    sprintf(
                        'Invalid scheme: "%s". Allowed values: "mail", "sendmail", "qmail", "smtp", "smtps".',
                        $connfig['scheme']
                    )
                );
        }

        if (isset($connfig['query'])) {
            $this->configureOptions($mailer, $connfig['query']);
        }
    }

    /**
     * Configure SMTP.
     *
     * @param PHPMailer $mailer PHPMailer instance
     * @param array     $connfig Configuration
     */
    private function configureSMTP($mailer, $connfig)
    {
        $isSMTPS = 'smtps' === $connfig['scheme'];

        if ($isSMTPS) {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mailer->Host = $connfig['host'];

        if (isset($connfig['port'])) {
            $mailer->Port = $connfig['port'];
        } elseif ($isSMTPS) {
            $mailer->Port = SMTP::DEFAULT_SECURE_PORT;
        }

        $mailer->SMTPAuth = isset($connfig['user']) || isset($connfig['pass']);

        if (isset($connfig['user'])) {
            $mailer->Username = $connfig['user'];
        }

        if (isset($connfig['pass'])) {
            $mailer->Password = $connfig['pass'];
        }
    }

    /**
     * Configure options.
     *
     * @param PHPMailer $mailer  PHPMailer instance
     * @param array     $options Options
     *
     * @throws Exception If option is unknown
     */
    private function configureOptions(PHPMailer $mailer, $options)
    {
        $allowedOptions = get_object_vars($mailer);

        unset($allowedOptions['Mailer']);
        unset($allowedOptions['SMTPAuth']);
        unset($allowedOptions['Username']);
        unset($allowedOptions['Password']);
        unset($allowedOptions['Hostname']);
        unset($allowedOptions['Port']);
        unset($allowedOptions['ErrorInfo']);

        $allowedOptions = \array_keys($allowedOptions);

        foreach ($options as $key => $value) {
            if (!in_array($key, $allowedOptions)) {
                throw new Exception(
                    sprintf(
                        'Unknown option: "%s". Allowed values: "%s"',
                        $key,
                        implode('", "', $allowedOptions)
                    )
                );
            }

            switch ($key) {
                case 'AllowEmpty':
                case 'SMTPAutoTLS':
                case 'SMTPKeepAlive':
                case 'SingleTo':
                case 'UseSendmailOptions':
                case 'do_verp':
                case 'DKIM_copyHeaderFields':
                    $mailer->$key = (bool) $value;
                    break;
                case 'Priority':
                case 'SMTPDebug':
                case 'WordWrap':
                    $mailer->$key = (int) $value;
                    break;
                default:
                    $mailer->$key = $value;
                    break;
            }
        }
    }

    /**
     * Parse a URL.
     * Wrapper for the built-in parse_url function to work around a bug in PHP 5.5.
     *
     * @param string $url URL
     *
     * @return array|false
     */
    protected function parseUrl($url)
    {
        if (\PHP_VERSION_ID >= 50600 || false === strpos($url, '?')) {
            return parse_url($url);
        }

        $chunks = explode('?', $url);
        if (is_array($chunks)) {
            $result = parse_url($chunks[0]);
            if (is_array($result)) {
                $result['query'] = $chunks[1];
            }
            return $result;
        }

        return false;
    }
}
