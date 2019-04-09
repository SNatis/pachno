<?php

    namespace pachno\core\modules\main\cli;

    use pachno\core\framework;

    /**
     * CLI command class, main -> license
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage core
     */

    /**
     * CLI command class, main -> clear-cache
     *
     * @package pachno
     * @subpackage core
     */
    class Upgrade extends \pachno\core\framework\cli\Command
    {

        protected function _setup()
        {
            $this->_command_name = 'upgrade';
            $this->_description = "Upgrades the installation to the current version.";
        }

        public function do_execute()
        {
            list ($current_version, $upgrade_available) = framework\Settings::getUpgradeStatus();

            $this->cliEcho('Performing upgrade: ');
            $this->cliEcho($current_version, 'white', 'bold');
            $this->cliEcho(' -> ');
            $this->cliEcho(framework\Settings::getVersion(false), 'green', 'bold');
            $this->cliEcho("\n\n");

            if (!$upgrade_available) {
                $this->cliEcho('No upgrade necessary!', 'green');
                $this->cliEcho("\n");
                return;
            } else {
                try {
                    $upgrader = new \pachno\core\modules\installation\Upgrade();
                    $result = $upgrader->upgrade();
                    $this->cliEcho("\n");
                    if ($result) {
                        $this->cliEcho("Upgrade complete!\n");
                        unlink(PACHNO_PATH . 'upgrade');
                    } else {
                        $this->cliEcho("Upgrade failed!\n", 'red');
                    }
                } catch (\Exception $e) {
                    $this->cliEcho("\n");
                    $this->cliEcho("\n---------------------\n");
                    $this->cliEcho("An error occured during the upgrade:\n", 'red', 'bold');
                    $this->cliEcho($e->getMessage() . "\n");
                    $this->cliEcho("---------------------\n");
                }
            }
        }

    }
