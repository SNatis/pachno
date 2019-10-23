<?php

    namespace pachno\core\entities\tables;

    use b2db\Criteria;
    use b2db\Table;
    use b2db\Update;
    use pachno\core\entities\AgileBoard;
    use pachno\core\modules\installation\upgrade_413\AgileBoardsTable;

    /**
     * Agile boards table
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage tables
     */

    /**
     * Agile boards table
     *
     * @package pachno
     * @subpackage tables
     *
     * @method static AgileBoards getTable() Retrieves an instance of this table
     * @method AgileBoard selectById(integer $id) Retrieves an agile board
     *
     * @Table(name="agileboards")
     * @Entity(class="\pachno\core\entities\AgileBoard")
     */
    class AgileBoards extends ScopedTable
    {

        const B2DB_TABLE_VERSION = 1;

        const SCOPE = 'agileboards.scope';

        public function getAvailableProjectBoards($user_id, $project_id)
        {
            $query = $this->getQuery();
            $query->where('agileboards.project_id', $project_id);

            $criteria = new Criteria();
            $criteria->where('agileboards.user_id', $user_id);
            $criteria->or('agileboards.is_private', false);

            $query->and($criteria);

            return $this->select($query);
        }

        protected function migrateData(Table $old_table)
        {
            if ($old_table instanceof AgileBoardsTable) {
                $update = new Update();
                $update->add('agileboards.issue_field_values', serialize([]));

                $this->rawUpdate($update);
            }
        }

    }
