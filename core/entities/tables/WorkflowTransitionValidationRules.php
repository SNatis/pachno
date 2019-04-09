<?php

    namespace pachno\core\entities\tables;

    use pachno\core\framework;
    use b2db\Core,
        b2db\Criteria,
        b2db\Criterion;

    /**
     * Workflow transition validation rules table
     *
     * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
     * @version 3.1
     * @license http://opensource.org/licenses/MPL-2.0 Mozilla Public License 2.0 (MPL 2.0)
     * @package pachno
     * @subpackage tables
     */

    /**
     * Workflow transition validation rules table
     *
     * @package pachno
     * @subpackage tables
     *
     * @method static WorkflowTransitionValidationRules getTable() Return an instance of this table
     * @method \pachno\core\entities\WorkflowTransitionValidationRule selectById() Return a WorkflowTransitionValidationRule object
     *
     * @Table(name="workflow_transition_validation_rules")
     * @Entity(class="\pachno\core\entities\WorkflowTransitionValidationRule")
     */
    class WorkflowTransitionValidationRules extends ScopedTable
    {

        const B2DB_TABLE_VERSION = 1;
        const B2DBNAME = 'workflow_transition_validation_rules';
        const ID = 'workflow_transition_validation_rules.id';
        const SCOPE = 'workflow_transition_validation_rules.scope';
        const RULE = 'workflow_transition_validation_rules.rule';
        const TRANSITION_ID = 'workflow_transition_validation_rules.transition_id';
        const WORKFLOW_ID = 'workflow_transition_validation_rules.workflow_id';
        const RULE_VALUE = 'workflow_transition_validation_rules.rule_value';
        const PRE_OR_POST = 'workflow_transition_validation_rules.pre_or_post';

        public function getByTransitionID($transition_id)
        {
            $query = $this->getQuery();
            $query->where(self::SCOPE, framework\Context::getScope()->getID());
            $query->where(self::TRANSITION_ID, $transition_id);
            
            $actions = array('pre' => array(), 'post' => array());
            if ($res = $this->select($query, false))
            {
                foreach ($res as $rule)
                {
                    $actions[$rule->isPreOrPost()][$rule->getRule()] = $rule;
                }
            }
            
            return $actions;
        }

        protected function setupIndexes()
        {
            $this->addIndex('scope_transitionid', array(self::SCOPE, self::TRANSITION_ID));
        }

    }