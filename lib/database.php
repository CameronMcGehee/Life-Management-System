<?php

    // DATABASE FUNCTIONS ------------------------------------------------------------------------------------------------------------------------------------------

	/* The database class below is used everywhere for mySQL (using the mySQLi driver) database queries. A custom class has been made here for this to shorten
	   code elsewhere, as well as to provide more flexibility to what is actually done for each query. This also removes the need to provide a connection object into every function. */
	   
	class database {
		
		static function getConn() {
			$config = $GLOBALS['ULTISCAPECONFIG'];
			$conn = mysqli_connect($config['databaseServer'], $config['databaseUsername'], $config['databasePassword'], $config['databaseDb']);
			return $conn;
		}

		function __construct() {
			$this->conn = $this->getConn();
		}

		function mysqlQuery($queryString) {
			$result = mysqli_query($this->conn, $queryString);

			return $result;
		}

		function sanitize($data) {
			if (!empty($data)) {
				if (in_array(gettype($data), array("integer", "double", "string"))) {
					return mysqli_real_escape_string($this->conn, (string)$data);
				} elseif ($data === NULL) {
					return 'NULL';
				} elseif ($data === true) {
					return '1';
				} elseif ($data === false) {
					return '0';
				}
			}
			return $data;
		}

		function getLastError() {
			return mysqli_error($this->conn);
		}

		function select($tableName, $columns = '*', $parameters = '', $array = true) {

			if (is_array($columns)) {
				$columnsString = implode(", ", $columns);
			} else {
				$columnsString = $columns;
			}

			$queryString = "SELECT ".$columnsString." FROM ".$tableName;

			if ($parameters != '') {
				$queryString .= " ".$parameters;
			}

			$result = mysqli_query($this->conn, $queryString);

			if ($result !== false) {
				if (mysqli_num_rows($result) > 0) {
					if ($array == true) {
						$ReturnRows = array();
						while ($row = mysqli_fetch_assoc($result)) {
							array_push($ReturnRows, $row);
						}
						return $ReturnRows;
					} else {
						return $result;
					}
				}
			}

			return false;
			
		}

		function insert($tableName, $data) {
			$keys = array();
			$values = array();

			foreach ($data as $key => $value) {
				array_push($keys, $key);
				if ($value !== NULL) {
					array_push($values, "'".$value."'");
				} else {
					array_push($values, 'NULL');
				}
				
			}

			$keysString = implode(", ", $keys);
			$valuesString = implode(", ", $values);

			$this->mysqlQuery("INSERT INTO $tableName ($keysString) VALUES ($valuesString)");

			if (mysqli_affected_rows($this->conn) == 1) {
				return true;
			} else {
				return false;
			}
		}

		function copy($fromTable, $toTable, $toFromPairs, $parameters = '') {

			$fromColumns = array();
			$toColumns = array();

			// Set assignment strings

			foreach ($toFromPairs as $fromColumn => $toColumn) {
				array_push($fromColumns, $fromColumn);
				array_push($toColumns, $toColumn);
			}

			// Turn arrays into strings

			$toColumnsString = implode(', ', $toColumns);
			$fromColumnsString = implode(', ', $fromColumns);

			// Make start of query
			
			$queryString = "INSERT INTO $toTable ($toColumnsString) SELECT $fromColumnsString FROM $fromTable";

			// If parameters are set, add them to the end

			if ($parameters != "") {
				$queryString .= " ".$parameters;
			}

			// Query and return

			$result = $this->mysqlQuery($queryString);
			
			if ($result !== false) {
				return true;
			} else {
				return false;
			}

		}

		function update($tableName, $columnToDataPairs, $parameters = '', $appropriateUpdates = 0) {
			
			// Create assignments String

			$assignments = array();

			foreach ($columnToDataPairs as $column => $data) {
				if ($data !== NULL) {
					$queryDataString = "'".$data."'";
				} else {
					$queryDataString = 'NULL';
				}
				array_push($assignments, "$column = $queryDataString");
			}

			$assignmentsString = implode(", ", $assignments);
			
			// Make start of query

			$queryString = "UPDATE $tableName SET $assignmentsString";
			
			// If parameters are set, add them to the end

			if ($parameters != "") {
				$queryString .= " ".$parameters;
			}

			// Query and return

			$result = $this->mysqlQuery($queryString);

			if ($result !== false) {
				$affectedRows = mysqli_affected_rows($this->conn);
				if ($appropriateUpdates > 0) {
					if ($affectedRows == $appropriateUpdates) {
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			} else {
				return false;
			}

		}

		function delete($tableName, $parameters, $appropriateUpdates = 0) {

			// Make start of query

			$queryString = "DELETE FROM $tableName";
			
			/* If parameters are set, add them to the end
			*	
			*  Pramamters and WHERE clause required for DELETE to keep from deleting an entire table by accident
			*/

			if ($parameters != "" && strpos($parameters, 'WHERE') !== false) {
				
				$queryString .= " ".$parameters;

				// Query and return

				$result = $this->mysqlQuery($queryString);

				if ($result !== false) {
					$affectedRows = mysqli_affected_rows($this->conn);
					if ($appropriateUpdates > 0) {
						if ($affectedRows == $appropriateUpdates) {
							return true;
						} else {
							return false;
						}
					} else {
						return $affectedRows;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
			
		}
		
	}

?>
