<?php

namespace Rueckgrat\Model;

abstract class Entity
{

    /**
     * values
     *
     * Holds the values of the model in
     * associative array.
     *
     * @var mixed
     */
    protected $values = array();
    protected $relations = array();
    /**
     * constructor
     *
     * @access public
     * @param mixed $idOrColumnOrQuery
     * @param mixed $value
     * @throws \Exception
     */
    public function __construct(Repository $repository)
    {

        $this->repository = $repository;

    }

    /**
     * Get & Set
     *
     * Provides the setters
     * and getters for the database columns.
     *
     * @param string $getterOrSetter
     * @param mixed $values
     * @return mixed
     */
    public function __call($getterOrSetter, $values) {
        $columnName = substr($getterOrSetter, 3);
        $relationName = $this->isRelation($columnName);
        // is a column of the table
        if ($relationName == false) {
            $columnNameParts = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2',$columnName);
            $column = substr(strtolower(str_replace(' ', '_', $columnNameParts)), 1);
            $column = ':' . str_replace(':', '', $column);
            if (substr($getterOrSetter, 0, 3) == 'set') {
                $this->values[$column] = $values[0];
            }
            else if (substr($getterOrSetter, 0, 3) == 'get') {
                if (isset($this->values[$column]) == true)
                return utf8_decode($this->values[$column]);
            }
        }
        // is a related table
        else {
            if (substr($getterOrSetter, 0, 3) == 'get') {
                $methodName = 'get' . $relationName;
                $entities = $this->repository->$methodName($this);
                return $entities;
            }
        }
    }

    /**
     * get columns
     *
     * Returns the columns of the entity.
     *
     * @access public
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * get values
     *
     * Returns the values of the entity.
     *
     * @access public
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }
    public function setValues($values)
    {
        $this->values = $values;
    }
    protected function isRelation($column)
    {
        foreach ($this->relations as $relation) {
            if (is_array($relation))
            {
                foreach($relation as $relationSpellings) {
                    if ($relationSpellings == $column) {

                        return $relationSpellings;
                    }
                }
            }
            else {
                if ($relation == $column) {

                    return $relation;
                }
            }
        }

        return false;
    }
}
