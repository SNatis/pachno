<?php

    namespace pachno\core\entities;

    use pachno\core\entities\common\IdentifiableScoped;

    /**
     * Agile board column class
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage agile
     */

    /**
     * Agile board column class
     *
     * @package pachno
     * @subpackage agile
     *
     * @Table(name="\pachno\core\entities\tables\BoardColumns")
     */
    class BoardColumn extends IdentifiableScoped
    {

        /**
         * The name of the column
         *
         * @var string
         * @Column(type="string", length=200)
         */
        protected $_name;

        /**
         * Column description
         *
         * @var string
         * @Column(type="string", length=200)
         */
        protected $_description;

        /**
         * @var \pachno\core\entities\AgileBoard
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\AgileBoard")
         */
        protected $_board_id;

        /**
         * @var integer
         * @Column(type="integer", length=10)
         */
        protected $_sort_order;

        /**
         * @var integer
         * @Column(type="integer", length=10)
         */
        protected $_max_workitems;

        /**
         * @var integer
         * @Column(type="integer", length=10)
         */
        protected $_min_workitems;

        /**
         * Associated status ids
         *
         * @var array
         * @Column(type="serializable", length=500)
         */
        protected $_status_ids = array();

        public function getName()
        {
            return $this->_name;
        }

        public function setName($name)
        {
            $this->_name = $name;
        }

        public function getDescription()
        {
            return $this->_description;
        }

        public function hasDescription()
        {
            return (bool) ($this->getDescription() != '');
        }

        public function setDescription($description)
        {
            $this->_description = $description;
        }

        /**
         * Returns the associated project
         *
         * @return \pachno\core\entities\AgileBoard
         */
        public function getBoard()
        {
            return $this->_b2dbLazyLoad('_board_id');
        }

        public function setBoard($board)
        {
            $this->_board_id = $board;
        }

        function getMaxWorkitems()
        {
            return $this->_max_workitems;
        }

        function getMinWorkitems()
        {
            return $this->_min_workitems;
        }

        function setMaxWorkitems($max_workitems)
        {
            $this->_max_workitems = $max_workitems;
        }

        function setMinWorkitems($min_workitems)
        {
            $this->_min_workitems = $min_workitems;
        }

        public function getSortOrder()
        {
            return $this->_sort_order;
        }

        public function setSortOrder($sort_order)
        {
            $this->_sort_order = $sort_order;
        }

        public function getStatusIds()
        {
            return $this->_status_ids;
        }

        public function hasStatusId($status_id)
        {
            return in_array($status_id, $this->getStatusIds());
        }

        public function hasStatusIds()
        {
            return (count($this->getStatusIds()) > 0);
        }

        public function setStatusIds($status_ids)
        {
            $this->_status_ids = $status_ids;
        }

        public function isStatusIdTaken($status_id)
        {
            foreach ($this->getBoard()->getColumns() as $column)
            {
                if ($column->getID() != $this->getID() && $column->hasStatusId($status_id)) return true;
            }

            return false;
        }

        public function hasIssue(\pachno\core\entities\Issue $issue)
        {
            return in_array($issue->getStatus()->getID(), $this->getStatusIds());
        }

        public function getColumnOrRandomID()
        {
            return ($this->getID()) ? $this->getID() : md5(rand(0,1000000));
        }

    }
