<?php

namespace Rueckgrat\Model;

abstract class Repository
{

    protected $columnHooks = array();

    /**
     * databaseHandler
     *
     * the PDO database handler
     *
     * @access protected
     * @var \PDO
     */
    protected $databaseHandler = null;

    protected $exceptValues = array();

    /**
     * table name
     *
     * the name of the entity's table
     *
     * @access protected
     * @var string
     */
    protected $tableName = '';

    protected $modelName = '';

    /**
     * constructor
     *
     * @access public
     */
    public function __construct($pimple)
    {
        $this->pimple = $pimple;
        $this->databaseHandler = $this->pimple['databaseHandler'];
        $this->config = $this->pimple['config'];
        $this->tableName = $this->getTableName($this);
        $this->initializeTraits();
    }

    /**
     * get all
     *
     * Returns all entities in an array.
     *
     * @access public
     * @return mixed
     */
    public function getAll()
    {
        $query = 'SELECT * FROM ' . $this->tableName . ' ORDER BY ID ASC;';
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->createEntities($results);
    }
    public function delete($question) {
        $query = 'DELETE FROM question WHERE id=:id';
        $values = array();
        $values[':id'] = $question->getId();
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute($values);
    }

    protected function executeColumnHooks($values) {
        foreach ($this->columnHooks as $columnHook) {

        }
    }
    protected function executeQuery($query, $values = null)
    {
        $statement = $this->databaseHandler->prepare($query);
        $statement->execute($values);
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->createEntities($results);
    }

    public function create($idOrColumnOrQuery = null, $value = null)
    {

        $entityName = $this->config['general.namespace'] . '\\Model\\Entity\\' . $this->modelName;
        $model = new $entityName($this);
        // create entity for a given ID
        $values = array();
        if ($idOrColumnOrQuery != null && is_int($idOrColumnOrQuery) == true)
        {
            $query = 'SELECT * FROM ' . $this->tableName . ' WHERE id = ? LIMIT 1;';
            $statement = $this->databaseHandler->prepare($query);
            $statement->execute(array($idOrColumnOrQuery));
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($results[0]) == true)
            {
                foreach ($results[0] as $key => $value)
                {
                    $values[$key] = $value;
                }
                $model->setValues($values);
            }
        }
        // create entity for custom where clause
        else if ($idOrColumnOrQuery != null && is_string($idOrColumnOrQuery) == true && is_array($value) == false)
        {
            $query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $idOrColumnOrQuery . ' = ?;';
            $statement = $this->databaseHandler->prepare($query);
            $statement->execute(array($value));
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($results[0]) == true)
            {
                foreach ($results[0] as $key => $value)
                {
                    $values[$key] = $value;
                }
                $model->setValues($values);
            }
        }
        else if (is_string($idOrColumnOrQuery) == true && is_array($idOrColumnOrQuery) == true) {
            $query = 'SELECT * FROM ' . $this->tableName . ' ' . $idOrColumnOrQuery . ';';
            $statement = $this->databaseHandler->prepare($query);
            $statement->execute($value);
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (isset($results[0]) == false) {
                throw new \Exception(ucfirst($this::table) . ' with ' . $idOrColumnOrQuery . ' "' . $value . '" doesn\'t exist.' );
            }
            else {
                foreach ($results[0] as $key => $value) {
                    $values[$key] = $value;
                }
                $model->setValues($values);
            }
        }
        return $model;
    }
    /**
     * save
     *
     * Persists an entity to the database and returns
     * the id of the affected row.
     *
     * @access public
     * @param Entity $entity an entity related to the repository
     * @return int the id of the affected row
     */
    public function save(Entity $entity) {

        $query = 'INSERT INTO ' . $this->tableName . ' SET ';
        $columns = $this->createValueQueryString($entity->getValues());
        $query .= $columns . ', created_at = FROM_UNIXTIME(' . time() . '), updated_at = FROM_UNIXTIME(' . time() . ')';
        $query .= ' ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), ' . $columns . ', updated_at = FROM_UNIXTIME(' . time() . ')';
        $query .= ';';
        try {
            $values = $entity->getValues();
            print_r($values);
            $statement = $this->databaseHandler->prepare($query);
            $statement->execute($values);
            $lastInsertId = $this->databaseHandler->lastInsertId();
            $values['id'] = $lastInsertId;
            $entity->setValues($values);
            print_r($this->databaseHandler->errorCode());
            return $lastInsertId;
        }
        catch (\PDOException $e){
            echo $e->getMessage();
        }

    }

    /**
     * save
     *
     * Persists an entity to the database and returns
     * the id of the affected row.
     *
     * @access public
     * @param Entity $entity an entity related to the repository
     * @return int the id of the affected row
     */
    public function save2(Entity $entity) {

        $query = 'INSERT INTO ' . $this->tableName . ' SET ';
        $columns = '';
        $values = array();
        if (isset($this->values['id']) == true && $this->values['id'] != '') {
            $columns = 'id= :id, ';
        }
        foreach ($entity->getValues() as $column => $value) {
            if ($column == 'created_at' || $column == 'updated_at') {
                continue;
            }
            $columns .= $column . '= :' . $column . ', ';
            $columnName = ':' . $column;
            $values[$columnName] = $value;
        }
        $columns = substr_replace($columns ,"",-2);
        $query .= $columns . ', created_at = FROM_UNIXTIME(' . time() . '), updated_at = FROM_UNIXTIME(' . time() . ')';
        $query .= ' ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), ' . $columns . ', updated_at = FROM_UNIXTIME(' . time() . ')';
        $query .= ';';
        try {
            $statement = $this->databaseHandler->prepare($query);
            $statement->execute($values);
            $lastInsertId = $this->databaseHandler->lastInsertId();
            $values['id'] = $lastInsertId;
            $entity->setValues($values);
            //print_r($this->databaseHandler->errorCode());
            return $lastInsertId;
        }
        catch (\PDOException $e){
            echo $e->getMessage();
        }

    }

    /**
     * create entity
     *
     * Creates an entity from a database result.
     *
     * @param mixed $row
     * @param string $entityName
     * @return Entity
     */
    protected function createEntity($result, $entityName = null)
    {

        if ($entityName == null)
        {
            $entityName = $this->modelName;
            $repository = $this;
        }
        else
        {
            $fullRepositoryName = $this->config['general.namespace'] . '\\Model\\Repository\\' . $entityName;
            $repository = new $fullRepositoryName($this->pimple);
        }
        $fullEntityName = $this->config['general.namespace'] . '\\Model\\Entity\\' . $entityName;
        $entity = new $fullEntityName($repository);
        foreach ($result as $key => $value)
        {
            $entityColumns = $entity->getcolumns();
            foreach ($entityColumns as $entityColumn)
            {
                if ($entityColumn == $key)
                {
                    $entityColumnParts = explode('_', $entityColumn);
                    $setterColumn = '';
                    if (is_array($entityColumnParts) == true)
                    {
                        foreach ($entityColumnParts as $entityColumnPart)
                        {
                            $setterColumn .= ucfirst($entityColumnPart);
                        }
                    }
                    else
                    {
                        $setterColumn = ucfirst($entityColumn);
                    }
                    $setter = 'set' . $setterColumn;
                    $entity->$setter($value);
                }
            }
        }

        return $entity;
    }

    /**
     * create entities
     *
     * Creates the entities from the database results.
     *
     * @param mixed $rows
     * @param string $entityName
     * @return mixed
     */
    protected function createEntities($results, $entityName = null) {

        $entities = array();
        foreach ($results as $result) {
            $entities[] = $this->createEntity($result, $entityName);
        }
        return $entities;

    }

    protected function createValueQueryString($values) {
        $columns = '';
//        if (isset($this->values['id']) == true && $this->values['id'] != '') {
//            $columns = 'id= :id, ';
//        }
        foreach ($values as $column => $value) {
            if ($column == 'created_at' || $column == 'updated_at' || $value === null || array_search($value, $this->exceptValues)) {
                continue;
            }
            $columns .= $column . '= :' . $column . ', ';
            $columnName = ':' . $column;
            $values[$columnName] = $value;
        }
        $columns = substr_replace($columns ,"",-2);

        return $columns;
    }

    /**
     * get table name
     *
     * Returns the name of the repository's table.
     *
     * @access protected
     * @param Repository $repository
     * @return string
     */
    protected function getTableName(Repository $repository)
    {
        $repositoryClassName = get_class($repository);
        $fullRepositoryName = str_replace($this->config['general.namespace'] . '\\Model\\Repository\\', '', $repositoryClassName);
        $this->modelName = ucfirst($fullRepositoryName);

        return lcfirst($fullRepositoryName);
    }

    protected function initializeTraits()
    {
//        $traitNames = get_declared_traits();
//        if ($traitNames != null) {
//            foreach ($traitNames as $traitName) {
//                $method = lcfirst($traitName);
//                $this->$method();
//            }
//        }
    }

}
