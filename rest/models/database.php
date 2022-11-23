<?php
    // Require Globals
    require_once(dirname(__DIR__, 1) . "/bootstrap.php");
    // Require Config
    require_once(PROJECT_ROOT . "config.php");

    abstract class DatabaseModel {
        private $db;

        abstract protected function getTableName();
        abstract protected function getPrimaryKey();
        abstract protected function getColumnNames();

        public function __construct() {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=". DB_NAME;
                $this->db = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                throw new Error\Server("Could not connect to database, PDO said: " . $e->getMessage());
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }
        
        public function execute($query) {
            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                throw new Error\Server("Could not read from database, PDO said: " . $e->getMessage());
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }

        public function create($entries) {
            try {
                $columnNames = $this->getColumnNames();

                foreach ($entries as $entry)
                    foreach ($entry as $key => $value)
                        if (!in_array($key, $columnNames)) throw new Error\Client("Unknown table property: $key");

                $keys = implode(", ", $columnNames);
                $placeholders = implode(", ", array_fill(0, count($columnNames), "?"));

                $query = "INSERT INTO " . $this->getTableName() . " ($keys) VALUES ($placeholders)";
                $stmt = $this->db->prepare($query);

                $response = [];
                foreach ($entries as $entry) {
                    for ($i = 0; $i < count($columnNames); $i++) {
                        if (array_key_exists($columnNames[$i], $entry)) {
                            $stmt->bindParam($i+1, $entry[$columnNames[$i]]);
                        } else {
                            $stmt->bindValue($i+1, null);
                        }
                    }
                    $stmt->execute();
                    array_push($response, $stmt->fetchAll());
                }

                return $response;
            } catch (PDOException $e) {
                throw new Error\Server("Could not create entry, PDO said: " . $e->getMessage());
            } catch (Error\Client $e) {
                throw $e;
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }

        // $options = [
        //     "ids" => [Primary keys of the rows you want to fetch],
        //     "limit" => integer, to limit the number of rows returned,
        //     "order" = integer, 0 for ascending, 1 for descending,
        // ]
        public function read($options) {
            try {
                $query = "SELECT * FROM " . $this->getTableName();
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                throw new Error\Server("Could not read from database, PDO said: " . $e->getMessage());
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }

        public function update($primaryKeyValues, $keyValuePairs) {
            $tableName = $this->getTableName();
            $primaryKey = $this->getPrimaryKey();
            $columnNames = $this->getColumnNames();

            $assignments = [];
            foreach ($keyValuePairs as $keyValuePair) {
                foreach ($keyValuePair as $key => $value) {
                    if (!in_array($key, $columnNames)) throw new Error\Client("Unknown table property: $key");
                    array_push($assignments, "$key=$value");
                }
            }

            $primaryKeyValuesStr = implode(", ", $primaryKeyValues);
            $assignmentsStr = implode(", ", $assignments);

            try {
                $query = "UPDATE $tableName SET $assignmentsStr WHERE $primaryKey IN ($primaryKeyValuesStr)";
                echo $query;
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                throw new Error\Server("Could not update entry, PDO said: " . $e->getMessage());
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }

        public function delete($primaryKeyValues) {
            $primaryKeys = "";
            $primaryKeys = implode(", ", $primaryKeyValue);

            try {
                $query = "DELETE FROM " . $this->getTableName() . " WHERE " . $this->getPrimaryKey() . " IN (" . $primaryKey . ")";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                throw new Error\Server("Could not read from database, PDO said: " . $e->getMessage());
            } catch (Exception $e) {
                throw new Error\Server("Uncategorised error, message: " . $e->getMessage());
            }
        }

    }
?>