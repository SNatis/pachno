<?php

    namespace pachno\core\entities\tables;

    use b2db\Query;
    use b2db\Row;
    use b2db\Table;
    use pachno\core\framework;

    /**
     * B2DB class that all  class extends, implementing scope access
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage mvc
     */

    /**
     * B2DB class that all  class extends, implementing scope access
     *
     * @method static static getTable()
     *
     * @package pachno
     * @subpackage mvc
     */
    class ScopedTable extends Table
    {

        /**
         * Return a row for the specified id in the current scope, if defined
         *
         * @param integer $id
         *
         * @return Row
         */
        public function getByID($id)
        {
            if (defined('static::SCOPE')) {
                $query = $this->getQuery();
                $query->where(static::SCOPE, $this->getCurrentScopeID());
                $row = $this->rawSelectById($id, $query);
            } else {
                $row = $this->rawSelectById($id);
            }

            return $row;
        }

        protected function getCurrentScopeID()
        {
            return framework\Context::getScope()->getID();
        }

        public function selectById($id, Query $query = null, $join = 'all')
        {
            $query = ($query instanceof Query) ? $query : $this->getQuery();
            $query->where(static::SCOPE, $this->getCurrentScopeID());

            return parent::selectById($id, $query, $join);
        }

        public function selectAll()
        {
            if (defined('static::SCOPE')) {
                $query = $this->getQuery();
                $query->where(static::SCOPE, $this->getCurrentScopeID());
                $results = $this->select($query);
            } else {
                $results = parent::selectAll();
            }

            return $results;
        }

        public function deleteFromScope($scope)
        {
            $query = $this->getQuery();
            if (defined('static::SCOPE')) {
                $query->where(static::SCOPE, $scope);
            }
            $res = $this->rawDelete($query);

            return $res;
        }

        protected function setup($b2db_name, $id_column)
        {
            parent::setup($b2db_name, $id_column);
            parent::addForeignKeyColumn(static::SCOPE, Scopes::getTable(), Scopes::ID);
        }

        protected function getCurrentScope()
        {
            return framework\Context::getScope();
        }

    }