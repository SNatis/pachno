<?php

    namespace pachno\core\entities;

    use pachno\core\entities\common\Releaseable;
    use pachno\core\entities\tables\LogItems;
    use pachno\core\framework;

    /**
     * Class used for builds/versions
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage main
     */

    /**
     * Class used for builds/versions
     *
     * @package pachno
     * @subpackage main
     *
     * @method static tables\Builds getB2DBTable()
     *
     * @Table(name="\pachno\core\entities\tables\Builds")
     */
    class Build extends Releaseable
    {

        /**
         * The name of the object
         *
         * @var string
         * @Column(type="string", length=200)
         */
        protected $_name;

        /**
         * This builds edition
         *
         * @var Edition
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\Edition")
         */
        protected $_edition = null;

        /**
         * This builds project
         *
         * @var Project
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\Project")
         */
        protected $_project = null;

        /**
         * This builds milestone, if any
         *
         * @var Milestone
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\Milestone")
         */
        protected $_milestone = null;

        /**
         * Whether this build is active or not
         *
         * @var boolean
         * @Column(type="boolean", name="locked")
         */
        protected $_isactive = null;

        /**
         * An attached file, if exists
         *
         * @var File
         * @Column(type="integer", length=10)
         * @Relates(class="\pachno\core\entities\File")
         */
        protected $_file_id = null;

        /**
         * An url to download this releases file, if any
         *
         * @var string
         * @Column(type="string", length=255)
         */
        protected $_file_url = null;

        /**
         * Major version
         *
         * @var integer
         * @access protected
         * @Column(type="integer", length=5)
         */
        protected $_version_major = 0;

        /**
         * Minor version
         *
         * @var integer
         * @access protected
         * @Column(type="integer", length=5)
         */
        protected $_version_minor = 0;

        /**
         * Revision
         *
         * @var integer
         * @access protected
         * @Column(type="string", length=30)
         */
        protected $_version_revision = 0;

        /**
         * Whether the item is locked or not
         *
         * @var boolean
         * @access protected
         * @Column(type="boolean")
         */
        protected $_locked;

        /**
         * Number of closed issues affected by this release
         *
         * @var integer
         */
        protected $_num_issues_closed = null;

        /**
         * Number of issues affected by this release
         *
         * @var integer
         */
        protected $_num_issues = null;

        public static function listen_pachno_core_entities_File_hasAccess(framework\Event $event)
        {
            $file = $event->getSubject();
            $builds = self::getB2DBTable()->getByFileID($file->getID());
            foreach ($builds as $build) {
                if ($build->hasAccess()) {
                    $event->setReturnValue(true);
                    $event->setProcessed();
                    break;
                }
            }
        }

        /**
         * Returns the name and the version, nicely formatted
         *
         * @return string
         */
        public function getPrintableName()
        {
            return $this->_name . ' (' . $this->getVersion() . ')';
        }

        /**
         * Returns the complete version number
         *
         * @return string
         */
        public function getVersion()
        {
            $versions = [$this->_version_major, $this->_version_minor];

            if ($this->_version_revision != 0) $versions[] = $this->_version_revision;

            return join('.', $versions);
        }

        public function getEditionID()
        {
            return ($this->_edition instanceof Edition) ? $this->_edition->getID() : (int)$this->_edition;
        }

        /**
         * Returns the milestone
         *
         * @return Milestone
         */
        public function getMilestone()
        {
            return $this->_b2dbLazyLoad('_milestone');
        }

        public function setMilestone(Milestone $milestone)
        {
            $this->_milestone = $milestone;
        }

        public function clearMilestone()
        {
            $this->_milestone = null;
        }

        public function clearEdition()
        {
            $this->_edition = null;
        }

        /**
         * Whether this build is under an edition
         *
         * @return bool
         */
        public function isEditionBuild()
        {
            return (bool)$this->_edition;
        }

        /**
         * Returns the parent object
         *
         * @return ReleaseableItem
         */
        public function getParent()
        {
            return ($this->isProjectBuild()) ? $this->getProject() : $this->getEdition();
        }

        /**
         * Whether this build is under a project
         *
         * @return bool
         */
        public function isProjectBuild()
        {
            return !is_null($this->_project);
        }

        /**
         * Returns the edition
         *
         * @return Edition
         */
        public function getEdition()
        {
            return $this->_b2dbLazyLoad('_edition');
        }

        public function setEdition(Edition $edition)
        {
            $this->_edition = $edition;
        }

        /**
         * Whether or not the current user can access the build
         *
         * @return boolean
         */
        public function hasAccess()
        {
            return ($this->isReleased() || $this->getProject()->canSeeInternalBuilds());
        }

        /**
         * Set the file associated with this build
         *
         * @param File $file
         */
        public function setFile(File $file)
        {
            $this->_file_id = $file;
        }

        public function clearFile()
        {
            $this->_file_id = null;
        }

        /**
         * Return whether this build has a file associated to it
         *
         * @return boolean
         */
        public function hasFile()
        {
            return (bool)($this->getFile() instanceof File);
        }

        /**
         * Return the file associated with this build, if any
         *
         * @return File
         */
        public function getFile()
        {
            return $this->_b2dbLazyLoad('_file_id');
        }

        /**
         * Return the file download url for this build
         *
         * @return string
         */
        public function getFileURL()
        {
            return $this->_file_url;
        }

        /**
         * Set the file download url for this build
         *
         * @param string $file_url
         */
        public function setFileURL($file_url)
        {
            $this->_file_url = $file_url;
        }

        /**
         * Return whether this build has a file url
         *
         * @return boolean
         */
        public function hasFileURL()
        {
            return (bool)($this->_file_url != '');
        }

        /**
         * Whether this build has any download associated with it
         *
         * @return boolean
         */
        public function hasDownload()
        {
            return (bool)($this->getFile() instanceof File || $this->_file_url != '');
        }

        public function isArchived()
        {
            return $this->isLocked();
        }

        /**
         * Returns whether or not this item is locked
         *
         * @return boolean
         * @access public
         */
        public function isLocked()
        {
            return $this->_locked;
        }

        /**
         * Specify whether or not this item is locked
         *
         * @param boolean $locked [optional]
         */
        public function setLocked($locked = true)
        {
            $this->_locked = (bool)$locked;
        }

        public function isActive()
        {
            return !$this->isLocked();
        }

        /**
         * Set the version
         *
         * @param integer $ver_mj Major version number
         * @param integer $ver_mn Minor version number
         * @param integer $ver_rev Version revision
         */
        public function setVersion($ver_mj, $ver_mn, $ver_rev)
        {
            $this->_version_major = ($ver_mj) ? $ver_mj : 0;
            $this->_version_minor = ($ver_mn) ? $ver_mn : 0;
            $this->_version_revision = ($ver_rev) ? $ver_rev : 0;
        }

        /**
         * Returns the major version number
         *
         * @return integer
         */
        public function getVersionMajor()
        {
            return $this->_version_major;
        }

        /**
         * Set the major version number
         *
         * @param $ver_mj
         */
        public function setVersionMajor($ver_mj)
        {
            $this->_version_major = ($ver_mj) ? $ver_mj : 0;
        }

        /**
         * Returns the minor version number
         *
         * @return integer
         */
        public function getVersionMinor()
        {
            return $this->_version_minor;
        }

        /**
         * Set the minor version number
         *
         * @param $ver_mn
         */
        public function setVersionMinor($ver_mn)
        {
            $this->_version_minor = ($ver_mn) ? $ver_mn : 0;
        }

        /**
         * Returns revision number
         *
         * @return mixed
         */
        public function getVersionRevision()
        {
            return $this->_version_revision;
        }

        /**
         * Set the version revision number
         *
         * @param $ver_rev
         */
        public function setVersionRevision($ver_rev)
        {
            $this->_version_revision = ($ver_rev) ? $ver_rev : 0;
        }

        /**
         * Return the items name
         *
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * Set the edition name
         *
         * @param string $name
         */
        public function setName($name)
        {
            $this->_name = $name;
        }

        public function getPercentComplete()
        {
            if ($this->getNumberOfAffectedIssues() == 0) {
                $pct = 0;
            } else {
                $multiplier = 100 / $this->getNumberOfAffectedIssues();
                $pct = $this->getNumberOfClosedIssues() * $multiplier;
            }

            return $pct;
        }

        public function getNumberOfAffectedIssues()
        {
            $this->_populateIssueCounts();

            return $this->_num_issues;
        }

        protected function _populateIssueCounts()
        {
            if ($this->_num_issues === null) {
                list($this->_num_issues, $this->_num_issues_closed) = tables\IssueAffectsBuild::getTable()->getCountsForBuild($this->getID());
            }
        }

        public function getNumberOfClosedIssues()
        {
            $this->_populateIssueCounts();

            return $this->_num_issues_closed;
        }

        protected function _postSave($is_new)
        {
            if ($is_new) {
                framework\Event::createNew('core', 'pachno\core\entities\Build::_postSave', $this)->trigger();
            }

            $this->generateLogItems();
        }

        public function generateLogItems()
        {
            $log_item = LogItems::getTable()->getByTargetAndChangeAndType($this->getID(), LogItem::ACTION_BUILD_RELEASED);
            if ($this->_release_date) {
                if (!$log_item instanceof LogItem) {
                    $log_item = new LogItem();
                    $log_item->setTargetType(LogItem::TYPE_BUILD);
                    $log_item->setTarget($this->getID());
                    $log_item->setChangeType(LogItem::ACTION_BUILD_RELEASED);
                    $log_item->setProject($this->getProject()->getID());
                }
                $log_item->setTime($this->_release_date);
                $log_item->save();
            } elseif ($log_item instanceof LogItem) {
                $log_item->delete();
            }
        }

        /**
         * Returns the project
         *
         * @return Project
         */
        public function getProject()
        {
            $this->_b2dbLazyLoad('_project');

            return $this->_project;
        }

        public function setProject(Project $project)
        {
            $this->_project = $project;
        }

        /**
         * Delete this build
         */
        protected function _preDelete()
        {
            tables\IssueAffectsBuild::getTable()->deleteByBuildID($this->getID());
        }

    }
