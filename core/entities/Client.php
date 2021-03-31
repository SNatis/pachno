<?php

    namespace pachno\core\entities;

    use Exception;
    use pachno\core\entities\common\IdentifiableScoped;
    use pachno\core\framework;

    /**
     * Client class
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage main
     */

    /**
     * Client class
     *
     * @package pachno
     * @subpackage main
     *
     * @Table(name="\pachno\core\entities\tables\Clients")
     */
    class Client extends IdentifiableScoped
    {

        protected static $_clients = null;

        protected $_members = null;

        protected $_num_members = null;

        /**
         * The name of the object
         *
         * @var string
         * @Column(type="string", length=200)
         */
        protected $_name;

        /**
         * Email of client
         *
         * @param string
         * @Column(type="string", length=200)
         */
        protected $_email = null;

        /**
         * Telephone number of client
         *
         * @param integer
         * @Column(type="string", length=200)
         */
        protected $_telephone = null;

        /**
         * URL for client website
         *
         * @param string
         * @Column(type="string", length=200)
         */
        protected $_website = null;

        /**
         * Fax number of client
         *
         * @param integer
         * @Column(type="string", length=200)
         */
        protected $_fax = null;

        /**
         * List of client's dashboards
         *
         * @var array|Dashboard
         * @Relates(class="\pachno\core\entities\Dashboard", collection=true, foreign_column="client_id", orderby="name")
         */
        protected $_dashboards = null;

        public static function doesClientNameExist($client_name)
        {
            return tables\Clients::getTable()->doesClientNameExist($client_name);
        }

        /**
         * @return Client[]
         */
        public static function getAll()
        {
            if (self::$_clients === null) {
                self::$_clients = tables\Clients::getTable()->getAll();
            }

            return self::$_clients;
        }

        public static function loadFixtures(Scope $scope)
        {

        }

        public function __toString()
        {
            return "" . $this->_name;
        }

        /**
         * Get the client's website
         *
         * @return string
         */
        public function getWebsite()
        {
            return $this->_website;
        }

        /**
         * Set the client's website
         *
         * @param string
         */
        public function setWebsite($website)
        {
            $this->_website = $website;
        }

        /**
         * Get the client's email address
         *
         * @return string
         */
        public function getEmail()
        {
            return $this->_email;
        }

        /**
         * Set the client's email address
         *
         * @param string
         */
        public function setEmail($email)
        {
            $this->_email = $email;
        }

        /**
         * Get the client's telephone number
         *
         * @return integer
         */
        public function getTelephone()
        {
            return $this->_telephone;
        }

        /**
         * Set the client's telephone number
         *
         * @param integer
         */
        public function setTelephone($telephone)
        {
            $this->_telephone = $telephone;
        }

        /**
         * Get the client's fax number
         *
         * @return integer
         */
        public function getFax()
        {
            return $this->_fax;
        }

        /**
         * Set the client's fax number
         *
         * @param integer
         */
        public function setFax($fax)
        {
            $this->_fax = $fax;
        }

        /**
         * Adds a user to the client
         *
         * @param User $user
         */
        public function addMember(User $user)
        {
            if (!$user->getID()) throw new Exception('Cannot add user object to client until the object is saved');

            tables\ClientMembers::getTable()->addUserToClient($user->getID(), $this->getID());

            if (is_array($this->_members))
                $this->_members[$user->getID()] = $user->getID();
        }

        public function getMembers()
        {
            if ($this->_members === null) {
                $this->_members = [];
                foreach (tables\ClientMembers::getTable()->getUIDsForClientID($this->getID()) as $uid) {
                    $this->_members[$uid] = User::getB2DBTable()->selectById($uid);
                }
            }

            return $this->_members;
        }

        public function removeMember(User $user)
        {
            if ($this->_members !== null) {
                unset($this->_members[$user->getID()]);
            }
            if ($this->_num_members !== null) {
                $this->_num_members--;
            }
            tables\ClientMembers::getTable()->removeUserFromClient($user->getID(), $this->getID());
        }

        public function getNumberOfMembers()
        {
            if ($this->_members !== null) {
                return count($this->_members);
            } elseif ($this->_num_members === null) {
                $this->_num_members = tables\ClientMembers::getTable()->getNumberOfMembersByClientID($this->getID());
            }

            return $this->_num_members;
        }

        public function hasAccess()
        {
            return (bool) framework\Context::getUser()->isMemberOfClient($this);
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

        /**
         * Returns an array of client dashboards
         *
         * @return Dashboard[]
         */
        public function getDashboards()
        {
            $this->_b2dbLazyLoad('_dashboards');

            return $this->_dashboards;
        }

        /**
         * @return Project[][]
         */
        public function getProjects()
        {
            $projects = Project::getAllByClientID($this->getID());

            $active_projects = [];
            $archived_projects = [];

            foreach ($projects as $project_id => $project) {
                if ($project->isArchived()) {
                    $archived_projects[$project_id] = $project;
                } else {
                    $active_projects[$project_id] = $project;
                }
            }

            return [$active_projects, $archived_projects];
        }

        protected function _preDelete()
        {
            tables\ClientMembers::getTable()->removeUsersFromClient($this->getID());
        }

    }
