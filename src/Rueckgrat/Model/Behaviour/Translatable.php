<?php

namespace Rueckgrat\Model\Behaviour;

/**
 *
 */
class Translatable
{

    protected $translatables = array();

    protected function translatable() {
        $model = $this->create();
        $this->translatables = $model->translatables;
        $this->excludeValues = array_merge($this->excludeValues, $this->translatables);
        $this->columnHooks[] = 'saveTranslations';
    }

    protected function saveTranslations($values) {
        $translationTable = $this->tableName . '_translation';
        foreach ($this->translatables as $translatable) {

        }
    }

    protected function isTranslatable() {

    }
}
