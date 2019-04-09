<?php

    namespace pachno\core\entities;

    use pachno\core\framework;

    /**
     * @Table(name="\pachno\core\entities\tables\ListTypes")
     */
    class Category extends common\Colorizable
    {

        const ITEMTYPE = Datatype::CATEGORY;

        protected $_itemtype = Datatype::CATEGORY;

        public static function loadFixtures(Scope $scope)
        {
            $categories = array('General' => '', 'Security' => '', 'User interface' => '');
            $categories['General'] = '#FFFFFF';
            $categories['Security'] = '#C2F533';
            $categories['User interface'] = '#55CC55';

            foreach ($categories as $name => $color)
            {
                $category = new Category();
                $category->setName($name);
                $category->setColor($color);
                $category->setScope($scope);
                $category->save();
            }
        }

        /**
         * Whether or not the current or target user can access the category
         *
         * @param null $target_user
         * @return boolean
         */
        public function hasAccess($target_user = null)
        {
            $user = ($target_user === null) ? framework\Context::getUser() : $target_user;

            return $this->canUserSet($user);
        }

    }
