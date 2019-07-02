<?php

    namespace pachno\core\entities;

    use pachno\core\entities\common\IdentifiableScoped;
    use pachno\core\entities\tables\Builds;
    use pachno\core\entities\tables\Commits;
    use pachno\core\entities\tables\Issues;
    use pachno\core\entities\tables\Milestones;

    /**
     * Log item class
     *
     * @package pachno
     * @subpackage main
     *
     * @Table(name="\pachno\core\entities\tables\LogItems")
     */
    class LogItem extends IdentifiableScoped
    {

        const ACTION_MILESTONE_STARTED = 1;
        const ACTION_MILESTONE_REACHED = 2;

        const ACTION_ISSUE_UPDATE_STATUS = 3;
        const ACTION_ISSUE_UPDATE_USER_WORKING_ON_ISSUE = 4;
        const ACTION_ISSUE_UPDATE_FREE_TEXT = 5;
        const ACTION_ISSUE_UPDATE_ISSUETYPE = 6;
        const ACTION_ISSUE_UPDATE_CATEGORY = 7;
        const ACTION_ISSUE_UPDATE_REPRODUCABILITY = 8;
        const ACTION_ISSUE_UPDATE_PERCENT_COMPLETE = 9;
        const ACTION_ISSUE_UPDATE_ESTIMATED_TIME = 10;
        const ACTION_ISSUE_UPDATE_RELATED_ISSUE = 11;
        const ACTION_ISSUE_UPDATE_RESOLUTION = 12;
        const ACTION_ISSUE_UPDATE_PRIORITY = 13;
        const ACTION_ISSUE_CLOSE = 14;
        const ACTION_ISSUE_ADD_AFFECTED_ITEM = 15;
        const ACTION_ISSUE_UPDATE_AFFECTED_ITEM = 16;
        const ACTION_ISSUE_REMOVE_AFFECTED_ITEM = 17;
        const ACTION_BUILD_RELEASED = 18;
        const LOG_TASK_UPDATE = 19;
        const LOG_TASK_DELETE = 20;
        const ACTION_ISSUE_UPDATE_TEAM = 21;
        const ACTION_ISSUE_REOPEN = 22;
        const LOG_TASK_COMPLETED = 23;
        const LOG_TASK_REOPENED = 24;
        const LOG_TASK_STATUS = 25;
        const LOG_TASK_ASSIGN_USER = 26;
        const LOG_TASK_ASSIGN_TEAM = 27;
        const ACTION_COMMENT_CREATED = 28;
        const ACTION_ISSUE_CREATED = 29;
        const ACTION_ISSUE_UPDATE_SEVERITY = 30;
        const ACTION_ISSUE_UPDATE_MILESTONE = 31;
        const ACTION_ISSUE_UPDATE_TIME_SPENT = 32;
        const ACTION_ISSUE_UPDATE_ASSIGNEE = 33;
        const ACTION_ISSUE_UPDATE_OWNER = 34;
        const ACTION_ISSUE_UPDATE_POSTED_BY = 35;
        const ACTION_ISSUE_UPDATE_CUSTOMFIELD = 36;
        const ACTION_ISSUE_UPDATE_PAIN_BUG_TYPE = 37;
        const ACTION_ISSUE_UPDATE_PAIN_EFFECT = 38;
        const ACTION_ISSUE_UPDATE_PAIN_LIKELIHOOD = 39;
        const ACTION_ISSUE_UPDATE_PAIN_SCORE = 40;
        const ACTION_ISSUE_ADD_BLOCKING = 41;
        const ACTION_ISSUE_REMOVE_BLOCKING = 42;
        const ACTION_ISSUE_UPDATE_TITLE = 43;
        const ACTION_ISSUE_UPDATE_DESCRIPTION = 44;
        const ACTION_ISSUE_UPDATE_REPRODUCTION_STEPS = 45;
        const ACTION_ISSUE_UPDATE_SHORT_LABEL = 46;

        const ACTION_ISSUE_UPDATE_COMMIT = 47;
        const ACTION_COMMIT_CREATED = 48;

        const TYPE_ISSUE = 1;
        const TYPE_COMMENT = 2;
        const TYPE_MILESTONE = 3;
        const TYPE_COMMIT = 4;
        const TYPE_ISSUE_COMMIT = 5;
        const TYPE_BUILD = 6;

        /**
         * @Column(type="integer", length=10)
         */
        protected $_target;

        protected $_target_object;

        /**
         * @Column(type="integer", length=10)
         */
        protected $_target_type;

        /**
         * @Column(type="integer", length=10)
         */
        protected $_change_type;

        /**
         * @Column(type="text")
         */
        protected $_previous_value;

        /**
         * @Column(type="text")
         */
        protected $_current_value;

        /**
         * @Column(type="text")
         */
        protected $_text;

        /**
         * @Column(type="integer", length=10)
         */
        protected $_time;

        /**
         * Who posted the comment
         *
         * @var \pachno\core\entities\User
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\User")
         */
        protected $_uid;

        /**
         * Related comment
         *
         * @var \pachno\core\entities\Comment
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\Comment")
         */
        protected $_comment_id;

        /**
         * Related project
         *
         * @var \pachno\core\entities\Project
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\Project")
         */
        protected $_project_id;

        protected function _preSave($is_new)
        {
            parent::_preSave($is_new);
            if ($is_new && !$this->_time)
            {
                $this->_time = NOW;
            }
        }

        public function getTarget()
        {
            return $this->_target;
        }

        public function setTarget($target)
        {
            $this->_target = $target;
        }

        public function getTargetType()
        {
            return $this->_target_type;
        }

        public function setTargetType($target_type)
        {
            $this->_target_type = $target_type;
        }

        public function getChangeType()
        {
            return $this->_change_type;
        }

        public function setChangeType($change_type)
        {
            $this->_change_type = $change_type;
        }

        public function getPreviousValue()
        {
            return $this->_previous_value;
        }

        public function setPreviousValue($previous_value)
        {
            $this->_previous_value = $previous_value;
        }

        public function getCurrentValue()
        {
            return $this->_current_value;
        }

        public function setCurrentValue($current_value)
        {
            $this->_current_value = $current_value;
        }

        public function getText()
        {
            return $this->_text;
        }

        public function setText($text)
        {
            $this->_text = $text;
        }

        public function getTime()
        {
            return $this->_time;
        }

        public function setTime($time)
        {
            $this->_time = $time;
        }

        /**
         * @return User
         */
        public function getUser()
        {
            return $this->_b2dbLazyLoad('_uid');
        }

        public function setUser($uid)
        {
            $this->_uid = $uid;
        }

        public function getComment()
        {
            return $this->_b2dbLazyLoad('_comment_id');
        }

        public function setComment($comment_id)
        {
            $this->_comment_id = $comment_id;
        }

        /**
         * @return Project
         */
        public function getProject()
        {
            return $this->_b2dbLazyLoad('_project_id');
        }

        public function setProject($project_id)
        {
            $this->_project_id = $project_id;
        }

        /**
         * @return Issue
         */
        public function getIssue()
        {
            if ($this->getTargetType() == LogItem::TYPE_ISSUE) {
                if (!$this->_target_object instanceof Issue) {
                    try {
                        $this->_target_object = Issues::getTable()->selectById($this->getTarget());
                    } catch (\Exception $e) { }
                }

                return $this->_target_object;
            }
        }

        /**
         * @return Milestone
         */
        public function getMilestone()
        {
            if ($this->getTargetType() == LogItem::TYPE_MILESTONE) {
                if (!$this->_target_object instanceof Milestone) {
                    try {
                        $this->_target_object = Milestones::getTable()->selectById($this->getTarget());
                    } catch (\Exception $e) { }
                }

                return $this->_target_object;
            }
        }

        /**
         * @return Build
         */
        public function getBuild()
        {
            if ($this->getTargetType() == LogItem::TYPE_BUILD) {
                if (!$this->_target_object instanceof Build) {
                    try {
                        $this->_target_object = Builds::getTable()->selectById($this->getTarget());
                    } catch (\Exception $e) { }
                }

                return $this->_target_object;
            }
        }

        /**
         * @return Commit
         */
        public function getCommit()
        {
            if ($this->getTargetType() == LogItem::TYPE_COMMIT) {
                if (!$this->_target_object instanceof Commit) {
                    try {
                        $this->_target_object = Commits::getTable()->selectById($this->getTarget());
                    } catch (\Exception $e) { }
                }

                return $this->_target_object;
            }
        }

        public function hasChangeDetails()
        {
            return ($this->_comment_id !== null);
        }

        public function isVisible()
        {
            if ($this->getTargetType() == self::TYPE_ISSUE) {
                if (!$this->getIssue() instanceof Issue) {
                    return false;
                }

                return $this->getIssue()->hasAccess();
            }

            return true;
        }

    }
