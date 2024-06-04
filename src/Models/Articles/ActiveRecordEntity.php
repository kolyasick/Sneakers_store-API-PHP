<?php 
namespace Src\Models\Articles;
use Src\Services\Db;

abstract class ActiveRecordEntity { // у абстрактного класс нельзя создать обьекты но можно наследовать 
    private $id;
    
    public function getId() {
        return $this->id;
    }

    private function underScoreToCamelCase(string $sourse): string {
        return lcfirst(str_replace('_', '', ucwords($sourse, '_')));
    }

    public static function findByOneColumn(string $columnName, $value): ?self{ 
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . 
            '` WHERE `' . $columnName . '` = :value LIMIT 1;', 
            [':value' => $value], static::class
        );
        if ($result === []) {
            return null;
        }
        return $result[0];
    }

    private function CamelCaseToUnderScore(string $sourse): string {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $sourse));
    }   

    private function mapPropertiesToDbFormat(): array {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderScore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
    }

    public function save(): void {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    private function update(array $mappedProperties): void {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index;
            $columns2params[] = $column . ' = ' . $param;
            $params2values[$param] = $value;
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    public function insert(array $mappedProperties): void {
        $filterProperties = array_filter($mappedProperties);
        $column = [];
        $paramsNames = [];
        foreach ($filterProperties as $columnName => $value) {
            $columns[] = '`' . $columnName . '`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $params2values[$paramName] = $value;
        }
        $columnsViaSemicolon = implode(', ', $columns);
        $paramsNamesViaSemicolon = implode(', ', $paramsNames);
        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . 
        $columnsViaSemicolon . ') VALUES (' . $paramsNamesViaSemicolon . ');';
        // var_dump($sql);
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();

        // INSERT INTO `articles` (`author_id`, `name`, `text`)
    }

    public static function findAll(): array {
        $db = Db::getInstance();    
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    public function delete(): void {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM ' . static::getTableName() . ' WHERE id=:id;', [':id' => $this->id]
        );
        $this->id = null;
    }

    public static function getById(int $id): ?self {
        $db = DB::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;', [':id' => $id], static::class
        );
        return $entities ? $entities[0] : null; 
    }


    public function __set($name, $value) { // магический метод, вызывается при попытке изменить значение несуществующего или скрытого свойства
        $camelCaseName = $this->underScoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }
    

    abstract protected static function getTableName(): string;
}