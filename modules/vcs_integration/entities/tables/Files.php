<?php

    namespace pachno\modules\vcs_integration\entities\tables;

    use pachno\core\entities\tables\ScopedTable;
    use \pachno\core\entities\Context;

/**
     * B2DB Table, vcs_integration -> VCSIntegrationFilesTable
     *
     * @author Philip Kent <kentphilip@gmail.com>
     * @version 3.2
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage vcs_integration
     */

    /**
     * B2DB Table, vcs_integration -> VCSIntegrationFilesTable
     *
     * @package pachno
     * @subpackage vcs_integration
     *
     * @Entity(class="\pachno\modules\vcs_integration\entities\File")
     * @Table(name="vcsintegration_files")
     */
    class Files extends ScopedTable
    {

        const B2DB_TABLE_VERSION = 2;
        const B2DBNAME = 'vcsintegration_files';
        const ID = 'vcsintegration_files.id';
        const SCOPE = 'vcsintegration_files.scope';
        const COMMIT_ID = 'vcsintegration_files.commit_id';
        const FILE_NAME = 'vcsintegration_files.file_name';
        const ACTION = 'vcsintegration_files.action';

        protected function _setupIndexes()
        {
            $this->_addIndex('commit', self::COMMIT_ID);
        }

        /**
         * Get all affected files by commit
         * @param integer $id
         */
        public function getByCommitID($id, $scope = null)
        {
            $scope = ($scope === null) ? \pachno\core\framework\Context::getScope()->getID() : $scope;
            $query = $this->getQuery();
            $query->where(self::SCOPE, $scope);
            $query->where(self::COMMIT_ID, $id);

            return $this->select($query);
        }

    }
