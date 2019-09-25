<?php

    namespace pachno\core\entities\tables;

    use b2db\Insertion;
    use b2db\Update;
    use pachno\core\framework;

/**
     * Modules table
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage tables
     */

    /**
     * Modules table
     *
     * @package pachno
     * @subpackage tables
     *
     * @method static Modules getTable() Retrieves an instance of this table
     * @method \pachno\core\entities\Module selectById(integer $id) Retrieves a module
     *
     * @Table(name="modules")
     * @Entity(class="\pachno\core\entities\Module")
     */
    class Modules extends ScopedTable
    {

        const B2DB_TABLE_VERSION = 1;
        const B2DBNAME = 'modules';
        const ID = 'modules.id';
        const MODULE_NAME = 'modules.name';
        const MODULE_LONGNAME = 'modules.module_longname';
        const ENABLED = 'modules.enabled';
        const VERSION = 'modules.version';
        const CLASSNAME = 'modules.classname';
        const SCOPE = 'modules.scope';

        public function getAll()
        {
            $query = $this->getQuery();
            $query->where(self::SCOPE, framework\Context::getScope()->getID());
            $modules = array();

            if ($res = $this->rawSelect($query))
            {
                while ($row = $res->getNextRow())
                {
                    $module_name = $row->get(self::MODULE_NAME);
                    $classname = "\\pachno\\modules\\{$module_name}\\".ucfirst($module_name);
                    if (class_exists($classname)) {
                        $modules[$module_name] = new $classname($row->get(self::ID), $row);
                    }
                }
            }

            return $modules;
        }

        public function getAllNames()
        {
            $query = $this->getQuery();
            $query->where(self::SCOPE, framework\Context::getScope()->getID());
            $query->addSelectionColumn(self::MODULE_NAME);
            $names = array();

            if ($res = $this->rawSelect($query))
            {
                while ($row = $res->getNextRow())
                {
                    $names[$row->get(self::MODULE_NAME)] = true;
                }
            }

            return $names;
        }

        public function disableModuleByID($module_id)
        {
            $update = new Update();
            $update->add(self::ENABLED, 0);
            return $this->rawUpdateById($update, $module_id);
        }

        public function setModuleVersion($module_key, $version)
        {
            $query = $this->getQuery();
            $update = new Update();

            $update->add(self::VERSION, $version);
            $query->where(self::MODULE_NAME, $module_key);

            return $this->rawUpdate($update, $query);
        }

        public function removeModuleByID($module_id)
        {
            return $this->rawDeleteById($module_id);
        }

        public function disableModuleByName($module_name, $all_scopes = false)
        {
            $query = $this->getQuery();
            $update = new Update();

            $update->add(self::ENABLED, 0);
            $query->where(self::MODULE_NAME, $module_name);

            if (!$all_scopes) {
                $query->where(self::SCOPE, framework\Context::getScope()->getID());
            }

            return $this->rawUpdate($update, $query);
        }

        public function removeModuleByName($module_name, $all_scopes = false)
        {
            $query = $this->getQuery();
            $query->where(self::MODULE_NAME, $module_name);

            if (!$all_scopes) {
                $query->where(self::SCOPE, framework\Context::getScope()->getID());
            }

            return $this->rawDelete($query);
        }

        public function installModule($identifier, $scope)
        {
            $classname = "\\pachno\\modules\\".$identifier."\\".ucfirst($identifier);
            if (!class_exists("\\pachno\\modules\\".$identifier."\\".ucfirst($identifier)))
            {
                throw new \Exception('Can not load new instance of type \\pachno\\modules\\'.$identifier."\\".ucfirst($identifier) . ', is not loaded');
            }

            $query = $this->getQuery();
            $query->where(self::MODULE_NAME, $identifier);
            $query->where(self::SCOPE, $scope);
            if (!$res = $this->rawSelectOne($query))
            {
                $insertion = new Insertion();
                $insertion->add(self::ENABLED, true);
                $insertion->add(self::MODULE_NAME, $identifier);
                $insertion->add(self::VERSION, $classname::VERSION);
                $insertion->add(self::SCOPE, $scope);
                $module_id = $this->rawInsert($insertion)->getInsertID();
            }
            else
            {
                $module_id = $res->get(self::ID);
            }

            $module = new $classname($module_id);
            return $module;
        }

        public function getModulesForScope($scope_id)
        {
            $query = $this->getQuery();
            $query->where(self::SCOPE, $scope_id);

            $return_array = array();
            if ($res = $this->rawSelect($query))
            {
                while ($row = $res->getNextRow())
                {
                    $return_array[$row->get(self::MODULE_NAME)] = (bool) $row->get(self::ENABLED);
                }
            }

            return $return_array;
        }

        public function getModuleForScope($module_name, $scope_id)
        {
            $query = $this->getQuery();
            $query->where(self::MODULE_NAME, $module_name);
            $query->where(self::SCOPE, $scope_id);

            $module = null;
            if ($row = $this->rawSelectOne($query))
            {
                $classname = $row->get(self::CLASSNAME);
                $module = new $classname($row->get(self::ID), $row);
            }

            return $module;
        }

    }
