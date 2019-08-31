<?php

namespace database;

class QueryBuilder {
    
    private $tableName;
    private $primaryKeyColumnName;
    
    public function __construct( $model, $primaryKeyColumnName = "id" ) {
        $this->tableName = $this->tableNameFromModelName(
            $model
        );

        $this->primaryKeyColumnName = $primaryKeyColumnName;
    }
    
    public function selectAll() {
        $query = "select * from " . $this->tableName;

        return array(
            "query" => $query
        );
    }

    public function deleteBy( $columnName, $columnValue ) {
        $query = sprintf("delete from %s where %s = :%s",
            $this->tableName, $columnName, $columnName
        );

        $params = array(
            ":" . $columnName => $columnValue
        );

        return array("query" => $query, "params" => $params);
    }
    
    public function deleteById( $idValue ) {
        $query = sprintf("delete from %s where %s = :%s",
            $this->tableName, $this->primaryKeyColumnName, $this->primaryKeyColumnName
        );

        $params = array(":" . $this->primaryKeyColumnName => $idValue);

        return array(
            "query" => $query,
            "params" => $params
        );
    }
    
    public function updateById( $model ) {
        return $this->updateAllBy($model, $this->primaryKeyColumnName);
    }
    
    public function updateAllBy( $model, $whereColumnName ) {
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $updateColsStr = "";
        $paramsForPreparedStatement = array();
        $isFirst = true;

        foreach ( $properties as $property ) {
            $property->setAccessible(true);

            if ( $this->isDBColumnProperty($property) and !$this->isCol($property, $whereColumnName) ) {
                $columnName = $property->getName();
                $valueName = ":" . $columnName;
                $paramsForPreparedStatement[$valueName] = $property->getValue($model);

                if ( $isFirst ) {
                    $updateColsStr .= "`".$columnName . "` =" . $valueName;
                    $isFirst = false;
                } else {
                    $updateColsStr .= ", `" . $columnName . "` =" . $valueName;
                }
                
            } else if ( $this->isCol($property, $whereColumnName) ) {
                $columnName =$property->getName();
                $valueName = ":" . $columnName;
                $paramsForPreparedStatement[$valueName] = $property->getValue($model);
            }
        }

        $where = $whereColumnName . "=:" . $whereColumnName;
        $query = sprintf("UPDATE %s SET %s WHERE %s", $this->tableName, $updateColsStr, $where);

        return array(
            "query" => $query,
            "params" => $paramsForPreparedStatement
        );
    }
    
    public function selectBy( $columnName, $columnValue, $orderBy = "", $orderValue = "" ) {
        if ( $orderBy != "" ) {
            $query = sprintf("select * from %s where %s = :%s order by %s %s",
                $this->tableName, $columnName, $columnName, $orderBy, $orderValue
            );
        } else {
            $query = sprintf("select * from %s where %s = :%s",
                $this->tableName, $columnName, $columnName
            );
        }

        $params = array(
            ":" . $columnName => $columnValue
        );

        return array("query" => $query, "params" => $params);
    }

    public function selectByNullField( $columnName ) {
        return sprintf("select * from %s where %s is null",
            $this->tableName, $columnName
        );
    }

    public function selectByNotNullField( $columnName ) {
        return sprintf("select * from %s where %s is not null",
            $this->tableName, $columnName
        );
    }
    
    public function selectById( $idValue ) {
        return $this->selectBy($this->primaryKeyColumnName, $idValue);
    }
    
    public function insertFromModel( $model ) {
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $valuesArray = array();
        $columnNamesArray = array();
        $valuesForPreparedStatement = array();

        foreach ( $properties as $property ) {
            $property->setAccessible(true);

            if ( $this->isDBColumnProperty($property) and !$this->isPrimaryKey($property) ) {
                $columnName =$property->getName();
                $valueName = ":" . $columnName;
                $columnNamesArray[] = "`" . $columnName . "`";
                $valuesArray[] = $valueName;
                $valuesForPreparedStatement[$valueName] = $property->getValue($model);
            }
        }

        $columnNames = implode(",", $columnNamesArray);
        $valuesForPDO = implode(",", $valuesArray);

        $query = sprintf("INSERT INTO %s (%s) VALUES (%s)", $this->tableName, $columnNames, $valuesForPDO);

        return array(
           "query" => $query,
           "params" => $valuesForPreparedStatement
        );
    }
        
    public function findByIdUseJoinFromModel( $model, $foreignModels = array(), $joinType = "INNER" ) {
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $className = self::classOf($model);
        $query = "SELECT %s FROM %s WHERE %s";
        $foreignKeys = $this->findForeignKeysInPropertiesOf($model);
        $relatedTables = $this->tableName . " AS `". $className ."`";
        $columns = $this->listAllDBColumnsForSelectOf($model, $className);

        foreach ( $foreignModels as $foreignModel ) {
            $classNameFK = self::classOf($foreignModel);
            $columns .= ", " . $this->listAllDBColumnsForSelectOf($foreignModel, $classNameFK);
            $relatedTables .= " " . $joinType . " JOIN " . $this->tableNameFromModelName($classNameFK) . " AS `" . $classNameFK . "`";

            if ( array_key_exists($classNameFK, $foreignKeys) ) {
                $relatedTables .= " ON `" . $className . "`." . $foreignKeys[$classNameFK] . "=" . "`" . $classNameFK . "`.id ";
            } else {
                throw new \Exception("No hay una relacion definida con $classNameFK en $className");
            }
        }

        $where = "`" . $className . "`.`"  . $this->primaryKeyColumnName . "` = :" . $this->primaryKeyColumnName;
        $query = sprintf($query, $columns, $relatedTables, $where);
        $valuesForPreparedStatement =array(":" . $this->primaryKeyColumnName => $model->getId());
        
        return array(
            "query" => $query,
            "params" => $valuesForPreparedStatement
        );
    }
        
    public function selectAllUseJoinFromModel( $model, $foreignModels = array(), $joinType = "INNER", $whereAnd = array() ){
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $className = self::classOf($model);
        $query = "SELECT %s FROM %s  WHERE 1 %s";
        $foreignKeys = $this->findForeignKeysInPropertiesOf($model);
        $relatedTables = $this->tableName . " AS `". $className ."`";
        $columns = $this->listAllDBColumnsForSelectOf($model, $className);
        $whereStr = "";

        foreach ( $whereAnd as $value ) {
            $whereStr .= " AND " . $value;
        }
        
        foreach ( $foreignModels as $foreignModel ) {
            $classNameFK = self::classOf($foreignModel);
            $columns .= ", " . $this->listAllDBColumnsForSelectOf($foreignModel, $classNameFK);
            $relatedTables .= " " . $joinType . " JOIN " . $this->tableNameFromModelName($classNameFK) . " AS `" . $classNameFK . "`";

            if ( array_key_exists($classNameFK, $foreignKeys) ) {
                $relatedTables .= " ON `" . $className . "`." . $foreignKeys[$classNameFK] ."=" . "`" . $classNameFK . "`.id ";
            } else {
                throw new \Exception("No hay una relacion definida con $classNameFK en $className");
            }
        }

        $query = sprintf($query, $columns, $relatedTables, $whereStr);
        
        return array(
            "query" => $query
        );
    }
        
    private function findForeignKeysInPropertiesOf( $model ) {
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $foreignKeys = array();
        
        foreach ( $properties as $property ) {
            $property->setAccessible(true);
            $columnArray = array();
            $classArray = array();

            if ( preg_match("/@join\-column\((.+)\)/m", $property->getDocComment(), $columnArray) ) {
                if ( preg_match("/@var\s+([a-zA-Z]+)/m", $property->getDocComment(), $classArray) ) {
                    $foreignKeys[$classArray[1]] = $columnArray[1];
                }
            }

            $pregLastError = preg_last_error();

            if ( $pregLastError > 0 ) {
                Logger()->error("ERROR in preg_match " . $pregLastError);
            }
        }

        return $foreignKeys;
    }
    
    public function tableNameFromModelName( $modelClassName ) {
        $tableName = preg_replace('/(?<! )(?<!^)[A-Z]/','_$0', $modelClassName);
        $pregLastError = preg_last_error();

        if ( $pregLastError > 0 ) {
            Logger()->error("ERROR in preg_match " . $pregLastError);
        }

        return strtolower($tableName);
    }
    
    private function listAllDBColumnsForSelectOf( $model, $className ) {
        $reflectionClass = new \ReflectionClass($model);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        $isFirst = true;
        $columns = "";

        foreach ( $properties as $property ) {
            $property->setAccessible(true);

            if ( $this->isDBColumnProperty($property) ) {
                if ( $isFirst ) {
                    $isFirst = false;
                    $columns .="`" . $className . "`.`" . $property->getName() . "` AS `" . $className . "." . $property->getName() . "` ";
                    continue;
                }

                $columns .=", `" . $className . "`.`" . $property->getName() . "` AS `" . $className . "." . $property->getName() . "`";
            }
        }

        return $columns;
    }
    
    public static function classOf( $object ) {
        $qualifiedModelName = get_class($object);
        $splitted = explode("\\", $qualifiedModelName);

        return $splitted[count($splitted) - 1];
    }
    
    /**
     * 
     * @param \ReflectionProperty $property
     */
    private function isDBColumnProperty( $property ) {
        if ( strpos($property->getDocComment(), "db_column") == false ) {
            return false;
        }

        return true;
    }
    
    /**
     * 
     * @param \ReflectionProperty $property
     */
    private function isPrimaryKey( $property ) {
        return strcasecmp($property->getName(), $this->primaryKeyColumnName) == 0;
    }
    
    private function isCol( $property, $columnName ) {
        return strcasecmp($property->getName(), $columnName) == 0;
    }
}