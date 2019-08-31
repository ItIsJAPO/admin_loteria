<?php

namespace database;

class SimpleDAO {
    
    private $connectionKey;

    protected $primaryKeyField;
    
    /**
     * 
     * @var string
     */
    protected $classOfModel;
    
    /**
     * 
     * @var QueryBuilder
     */
    protected $queryBuilder;
    
    public function __construct( $model, $pkField = "id" ) {
        $this->connectionKey = 'DEFAULT';
        $this->classOfModel = get_class($model);

        $this->queryBuilder = new QueryBuilder(
            QueryBuilder::classOf($model), $pkField
        );

        $this->primaryKeyField = $pkField;
    }

	/**
	 * @param bool $model el valor por default es true para enviar a la vista un modelo de objetos, false para un array asociativo
	 * @return array o un modelo de objetos de la tabla
	 */
	public function findAll($model = true) {
        $query = $this->queryBuilder->selectAll();

        $statement = Connections::getConnection()->prepare($query["query"]);
        if($model) {
	        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);

        }else $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();

        return $statement->fetchAll();
    }

	/**
	 * @param int $id valor entero.
	 * @param bool $model el valor por default es true para enviar a la vista un modelo de objetos, false para un array asociativo.
	 * @return array o un modelo de objetos de la tabla.
	 */
	public function findById($id , $model = true) {
        $query = $this->queryBuilder->selectById($id);

        $statement = Connections::getConnection()->prepare($query["query"]);
	    if($model) {
		    $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);

	    }else $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute($query["params"]);

        return $statement->fetch();
    }

	/**
	 * @param array $get_config es un array de configuración para traer información de una columna en especifico
	 * @param bool $model el valor por default es true para enviar a la vista un modelo de objetos, false para un array asociativo.
	 * @return array o un modelo de objetos de la tabla.
	 */
	public function findByField($get_config, $model = true) {
        $set_config = array(
            'orderValue' => isset($get_config['orderValue']) ? $get_config['orderValue'] : "",
            'fetchAll' => isset($get_config['fetchAll']) ? $get_config['fetchAll'] : false,
            'orderBy' => isset($get_config['orderBy']) ? $get_config['orderBy'] : "",
            'fieldValue' => $get_config['fieldValue'],
            'fieldName' => $get_config['fieldName']
        );

        $query = $this->queryBuilder->selectBy(
            $set_config['fieldName'], 
            $set_config['fieldValue'],
            $set_config['orderBy'],
            $set_config['orderValue']
        );

        $statement = Connections::getConnection()->prepare($query["query"]);
		if($model) {
			$statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);

		}else $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute($query["params"]);

        if ( $set_config['fetchAll'] ) {
            return $statement->fetchAll();
        }

        return $statement->fetch();
    }

    public function findByNullField( $fieldName, $all = false ) {
        $query = $this->queryBuilder->selectByNullField($fieldName);

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
        $statement->execute();

        if ( $all ) {
            return $statement->fetchAll();
        }

        return $statement->fetch();
    }

    public function findByNotNullField( $fieldName, $all = false ) {
        $query = $this->queryBuilder->selectByNotNullField($fieldName);

        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->classOfModel);
        $statement->execute();

        if ( $all ) {
            return $statement->fetchAll();
        }

        return $statement->fetch();
    }
        
    public function save( &$modelObject ) {
        $query = $this->queryBuilder->insertFromModel($modelObject);
        
        $statement = Connections::getConnection()->prepare($query["query"]);
        $this->bindAllParamsFromArray($query["params"], $statement);
        $statement->execute();

        $lastId = Connections::getConnection()->lastInsertId();

        if ( $this->primaryKeyField && method_exists($modelObject, 'setId') ) {
            $modelObject->setId($lastId);
        }
    }
    
    public function updateById( $modelObject ) {
        $query = $this->queryBuilder->updateById($modelObject);

        $statement = Connections::getConnection()->prepare($query["query"]);
        $this->bindAllParamsFromArray($query["params"], $statement);

        $statement->execute();
    }
    
    public function deleteById( $id ) {
        $query = $this->queryBuilder->deleteById($id);
        $statement = Connections::getConnection()->prepare($query["query"]);
        $statement->execute($query["params"]);
    }

    public function deleteByField( $columnName, $columnValue ) {
        $query = $this->queryBuilder->deleteBy($columnName, $columnValue);
        $statement = Connections::getConnection()->prepare($query["query"]);
        $statement->execute($query["params"]);
    }
    
    protected function bindAllParamsFromArray( $arrayOfParams, $statement ) {
        foreach ( $arrayOfParams as $paramKey => $paramValue ) {
            if ( is_null($paramValue) ) {
                $statement->bindValue($paramKey, NULL, \PDO::PARAM_INT);
            } else {
                $statement->bindValue($paramKey, $paramValue, \PDO::PARAM_STR);
            }
        }
    }
}