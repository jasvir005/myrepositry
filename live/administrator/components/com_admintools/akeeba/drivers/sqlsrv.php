<?php
/**
 * Akeeba Engine
 * The modular PHP5 site backup engine
 * @copyright Copyright (c)2009-2012 Nicholas K. Dionysopoulos
 * @license GNU GPL version 3 or, at your option, any later version
 * @package akeebaengine
 */

// Protection against direct access
defined('AKEEBAENGINE') or die('Restricted access');

/**
 * SQL Server database driver
 * 
 * Based on Joomla! Platform 11.2
 */
class AEDriverSqlsrv extends AEAbstractDriver
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 */
	public $name = 'sqlsrv';

	/**
	 * The character(s) used to quote SQL statement names such as table names or field names,
	 * etc.  The child classes should define this as necessary.  If a single character string the
	 * same character is used for both sides of the quoted name, else the first character will be
	 * used for the opening quote and the second for the closing quote.
	 *
	 * @var    string
	 */
	protected $nameQuote = '[]';

	/**
	 * The null or zero representation of a timestamp for the database driver.  This should be
	 * defined in child classes to hold the appropriate value for the engine.
	 *
	 * @var    string
	 */
	protected $nullDate = '1900-01-01 00:00:00';

	/**
	 * Test to see if the SQLSRV connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   11.1
	 */
	public static function test()
	{
		return (function_exists('sqlsrv_connect'));
	}

	/**
	 * Constructor.
	 *
	 * @param   array  $options  List of options used to configure the connection
	 *
	 * @since   11.1
	 */
	public function __construct($options)
	{
		$this->driverType = 'mssql';
		
		// Get some basic values from the options.
		$host		= array_key_exists('host', $options)	? $options['host']		: 'localhost';
		$port		= array_key_exists('port', $options)	? $options['port']		: '';
		$user		= array_key_exists('user', $options)	? $options['user']		: '';
		$password	= array_key_exists('password',$options)	? $options['password']	: '';
		$database	= array_key_exists('database',$options)	? $options['database']	: '';
		$prefix		= array_key_exists('prefix', $options)	? $options['prefix']	: '';
		$select		= array_key_exists('select', $options)	? $options['select']	: true;
		
		// Build the connection configuration array.
		$this->connectionConfig = array(
			'Database' => $database,
			'uid' => $user,
			'pwd' => $password,
			'CharacterSet' => 'UTF-8',
			'ReturnDatesAsStrings' => true
		);

		parent::__construct($options);
		
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->_database = $database;
		$this->selectDatabase = $select;
		
		if(!is_resource($this->connection)) $this->open();
	}
	
	public function open()
	{
		if(is_resource($this->connection)) return;
		
		$config = $this->connectionConfig;
		
		// Make sure the SQLSRV extension for PHP is installed and enabled.
		if (!function_exists('sqlsrv_connect'))
		{
			$this->errorNum = 1;
			$this->errorMsg = 'You do not have the sqlsrv extension installed on this server';
			return;
		}

		// Attempt to connect to the server.
		if (!($this->connection = @ sqlsrv_connect($this->host, $config)))
		{

			$this->errorNum = 2;
			$this->errorMsg = 'Can not connect to Microsoft SQL Server';
			return;
		}

		// Make sure that DB warnings are not returned as errors.
		sqlsrv_configure('WarningsReturnAsErrors', 0);

		// If auto-select is enabled select the given database.
		if ($this->selectDatabase && !empty($this->_database)) {
			$this->select($this->_database);
		}
	}

	public function close()
	{
		$return = false;
		if (is_resource($this->cursor)) {
			sqlsrv_free_result($this->cursor);
		}
		if (is_resource($this->connection)) {
			$return = sqlsrv_close($this->connection);
		}
		$this->connection = null;
		return $return;
	}

	/**
	 * Get table constraints
	 *
	 * @param   string  $tableName  The name of the database table.
	 *
	 * @return  array  Any constraints available for the table.
	 */
	protected function getTableConstraints($tableName)
	{
		$query = $this->getQuery(true);

		$this->setQuery(
			'SELECT CONSTRAINT_NAME FROM' . ' INFORMATION_SCHEMA.TABLE_CONSTRAINTS' . ' WHERE TABLE_NAME = ' . $query->quote($tableName)
		);

		return $this->loadColumn();
	}

	/**
	 * Rename constraints.
	 *
	 * @param   array   $constraints  Array(strings) of table constraints
	 * @param   string  $prefix       A string
	 * @param   string  $backup       A string
	 *
	 * @return  void
	 */
	protected function renameConstraints($constraints = array(), $prefix = null, $backup = null)
	{
		foreach ($constraints as $constraint)
		{
			$this->setQuery('sp_rename ' . $constraint . ',' . str_replace($prefix, $backup, $constraint));
			$this->query();
		}
	}

	/**
	 * Method to escape a string for usage in an SQL statement.
	 *
	 * The escaping for MSSQL isn't handled in the driver though that would be nice.  Because of this we need
	 * to handle the escaping ourselves.
	 *
	 * @param   string   $text   The string to be escaped.
	 * @param   boolean  $extra  Optional parameter to provide extra escaping.
	 *
	 * @return  string  The escaped string.
	 */
	public function escape($text, $extra = false)
	{
		// TODO: MSSQL Compatible escaping
		$result = addslashes($text);
		$result = str_replace("\'", "''", $result);
		$result = str_replace('\"', '"', $result);
		//$result = str_replace("\\", "''", $result);

		if ($extra)
		{
			// We need the below str_replace since the search in sql server doesn't recognize _ character.
			$result = str_replace('_', '[_]', $result);
		}

		return $result;
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @return  boolean  True if connected to the database engine.
	 */
	public function connected()
	{
		// TODO: Run a blank query here
		return true;
	}

	/**
	 * Drops a table from the database.
	 *
	 * @param   string   $tableName  The name of the database table to drop.
	 * @param   boolean  $ifExists   Optionally specify that the table must exist before it is dropped.
	 *
	 * @return  JDatabaseSQLSrv  Returns this object to support chaining.
	 */
	public function dropTable($tableName, $ifExists = true)
	{
		$query = $this->getQuery(true);

		if ($ifExists)
		{
			$this->setQuery(
				'IF EXISTS(SELECT TABLE_NAME FROM' . ' INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = ' . $query->quote($tableName) . ') DROP TABLE ' . $tableName
			);
		}
		else
		{
			$this->setQuery('DROP TABLE ' . $tableName);
		}

		$this->query();

		return $this;
	}

	/**
	 * Get the number of affected rows for the previous executed SQL statement.
	 *
	 * @return  integer  The number of affected rows.
	 */
	public function getAffectedRows()
	{
		return sqlsrv_rows_affected($this->cursor);
	}

	/**
	 * Method to get the database collation in use by sampling a text field of a table in the database.
	 *
	 * @return  mixed  The collation in use by the database or boolean false if not supported.
	 */
	public function getCollation()
	{
		// TODO: Not fake this
		return 'MSSQL UTF-8 (UCS2)';
	}

	/**
	 * Get the number of returned rows for the previous executed SQL statement.
	 *
	 * @param   resource  $cursor  An optional database cursor resource to extract the row count from.
	 *
	 * @return  integer   The number of returned rows.
	 */
	public function getNumRows($cursor = null)
	{
		return sqlsrv_num_rows($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Get the current or query, or new JDatabaseQuery object.
	 *
	 * @param   boolean  $new  False to return the last query set, True to return a new JDatabaseQuery object.
	 *
	 * @return  mixed  The current value of the internal SQL variable or a new JDatabaseQuery object.
	 */
	public function getQuery($new = false)
	{
		if ($new)
		{
			return new AEQuerySqlsrv($this);
		}
		else
		{
			return $this->sql;
		}
	}

	/**
	 * Retrieves field information about the given tables.
	 *
	 * @param   mixed    $table     A table name
	 * @param   boolean  $typeOnly  True to only return field types.
	 *
	 * @return  array  An array of fields.
	 */
	public function getTableColumns($table, $typeOnly = true)
	{
		// Initialise variables.
		$result = array();

		$table_temp = $this->replacePrefix((string) $table);
		// Set the query to get the table fields statement.
		$this->setQuery(
			'SELECT column_name as Field, data_type as Type, is_nullable as \'Null\', column_default as \'Default\'' .
			' FROM information_schema.columns' . ' WHERE table_name = ' . $this->quote($table_temp)
		);
		$fields = $this->loadObjectList();
		// If we only want the type as the value add just that to the list.
		if ($typeOnly)
		{
			foreach ($fields as $field)
			{
				$result[$field->Field] = preg_replace("/[(0-9)]/", '', $field->Type);
			}
		}
		// If we want the whole field data object add that to the list.
		else
		{
			foreach ($fields as $field)
			{
				$result[$field->Field] = $field;
			}
		}

		return $result;
	}

	/**
	 * Shows the table CREATE statement that creates the given tables.
	 *
	 * This is unsupported by MSSQL.
	 *
	 * @param   mixed  $tables  A table name or a list of table names.
	 *
	 * @return  array  A list of the create SQL for the tables.
	 */
	public function getTableCreate($tables)
	{
		// @todo Implement my own approximate function
		return '';
	}

	/**
	 * Get the details list of keys for a table.
	 *
	 * @param   string  $table  The name of the table.
	 *
	 * @return  array  An array of the column specification for the table.
	 */
	public function getTableKeys($table)
	{
		// TODO To implement.
		return array();
	}
	
	/**
	* Method to quote and optionally escape a string to database requirements for insertion into the database.
	*
	* @param   string   $text    The string to quote.
	* @param   boolean  $escape  True to escape the string, false to leave it unchanged.
	*
	* @return  string  The quoted input string.
	*/
	public function quote($text, $escape = true)
	{
		return 'N' . '\'' . ($escape ? $this->escape($text) : $text) . '\'';
	}
	
	/**
	 * Method to get an array of all tables in the database.
	 *
	 * @return  array  An array of all the tables in the database.
	 */
	public function getTableList()
	{
		// Set the query to get the tables statement.
		$this->setQuery('SELECT name FROM ' . $this->getDatabase() . '.sys.Tables WHERE type = \'U\';');
		$tables = $this->loadColumn();

		return $tables;
	}

	/**
	 * Get the version of the database connector.
	 *
	 * @return  string  The database connector version.
	 */
	public function getVersion()
	{
		//TODO: Don't hardcode this.
		return '5.1.0';
	}

	/**
	 * Determines if the database engine supports UTF-8 character encoding.
	 *
	 * @return  boolean  True if supported.
	 */
	public function hasUTF()
	{
		return true;
	}

	/**
	 * Inserts a row into a table based on an object's properties.
	 *
	 * @param   string  $table    The name of the database table to insert into.
	 * @param   object  &$object  A reference to an object whose public properties match the table fields.
	 * @param   string  $key      The name of the primary key. If provided the object property is updated.
	 *
	 * @return  boolean    True on success.
	 */
	public function insertObject($table, &$object, $key = null)
	{
		$fields = array();
		$values = array();
		$statement = 'INSERT INTO ' . $this->quoteName($table) . ' (%s) VALUES (%s)';
		foreach (get_object_vars($object) as $k => $v)
		{
			if (is_array($v) or is_object($v))
			{
				continue;
			}
			if (!$this->checkFieldExists($table, $k))
			{
				continue;
			}
			if ($k[0] == '_')
			{
				// internal field
				continue;
			}
			if ($k == $key && $key == 0)
			{
				continue;
			}
			$fields[] = $this->quoteName($k);
			$values[] = $this->Quote($v);
		}
		// Set the query and execute the insert.
		$this->setQuery(sprintf($statement, implode(',', $fields), implode(',', $values)));
		if (!$this->query())
		{
			return false;
		}
		$id = $this->insertid();
		if ($key && $id)
		{
			$object->$key = $id;
		}
		return true;
	}

	/**
	 * Method to get the auto-incremented value from the last INSERT statement.
	 *
	 * @return  integer  The value of the auto-increment field from the last inserted row.
	 */
	public function insertid()
	{
		$this->setQuery('SELECT @@IDENTITY');
		return (int) $this->loadResult();
	}

	/**
	 * Method to get the first field of the first row of the result set from the database query.
	 *
	 * @return  mixed  The return value or null if the query failed.
	 */
	public function loadResult()
	{
		// Initialise variables.
		$ret = null;

		// Execute the query and get the result set cursor.
		if (!($cursor = $this->query()))
		{
			return null;
		}

		// Get the first row from the result set as an array.
		if ($row = sqlsrv_fetch_array($cursor, SQLSRV_FETCH_NUMERIC))
		{
			$ret = $row[0];
		}
		// Free up system resources and return.
		$this->freeResult($cursor);
		//For SQLServer - we need to strip slashes
		$ret = stripslashes($ret);

		return $ret;
	}

	/**
	 * Execute the SQL statement.
	 *
	 * @return  mixed  A database cursor resource on success, boolean false on failure.
	 *
	 * @since   11.1
	 * @throws  JDatabaseException
	 */
	public function query()
	{
		if (!is_resource($this->connection))
		{

			return false;
		}

		// Take a local copy so that we don't modify the original query and cause issues later
		$sql = $this->replacePrefix((string) $this->sql);
		if ($this->limit > 0 || $this->offset > 0)
		{
			$sql = $this->limit($sql, $this->limit, $this->offset);
		}

		// Reset the error values.
		$this->errorNum = 0;
		$this->errorMsg = '';

		// sqlsrv_num_rows requires a static or keyset cursor.
		if (strncmp(ltrim(strtoupper($sql)), 'SELECT', strlen('SELECT')) == 0)
		{
			$array = array('Scrollable' => SQLSRV_CURSOR_KEYSET);
		}
		else
		{
			$array = array();
		}

		// Execute the query.
		$this->cursor = sqlsrv_query($this->connection, $sql, array(), $array);

		// If an error occurred handle it.
		if (!$this->cursor)
		{

			// Populate the errors.
			$errors = sqlsrv_errors();
			$this->errorNum = $errors[0]['SQLSTATE'];
			$this->errorMsg = $errors[0]['message'] . 'SQL=' . $sql;

			return false;
		}

		return $this->cursor;
	}
	/**
	 * This function replaces a string identifier <var>$prefix</var> with the string held is the
	 * <var>tablePrefix</var> class variable.
	 *
	 * @param   string  $sql     The SQL statement to prepare.
	 * @param   string  $prefix  The common table prefix.
	 *
	 * @return  string  The processed SQL statement.
	 *
	 * @since   11.1
	 */
	public function replacePrefix($sql, $prefix = '#__')
	{
		$tablePrefix = 'jos_';
		// Initialize variables.
		$escaped = false;
		$startPos = 0;
		$quoteChar = '';
		$literal = '';

		$sql = trim($sql);
		$n = strlen($sql);

		while ($startPos < $n)
		{
			$ip = strpos($sql, $prefix, $startPos);
			if ($ip === false)
			{
				break;
			}

			$j = strpos($sql, "N'", $startPos);
			$k = strpos($sql, '"', $startPos);
			if (($k !== false) && (($k < $j) || ($j === false)))
			{
				$quoteChar = '"';
				$j = $k;
			}
			else
			{
				$quoteChar = "'";
			}

			if ($j === false)
			{
				$j = $n;
			}

			$literal .= str_replace($prefix, $this->tablePrefix, substr($sql, $startPos, $j - $startPos));
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n)
			{
				break;
			}

			// quote comes first, find end of quote
			while (true)
			{
				$k = strpos($sql, $quoteChar, $j);
				$escaped = false;
				if ($k === false)
				{
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\')
				{
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped)
				{
					$j = $k + 1;
					continue;
				}
				break;
			}
			if ($k === false)
			{
				// error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr($sql, $startPos, $k - $startPos + 1);
			$startPos = $k + 1;
		}
		if ($startPos < $n)
		{
			$literal .= substr($sql, $startPos, $n - $startPos);
		}

		return $literal;
	}

	/**
	 * Select a database for use.
	 *
	 * @param   string  $database  The name of the database to select for use.
	 *
	 * @return  boolean  True if the database was successfully selected.
	 *
	 * @since   11.1
	 * @throws  JDatabaseException
	 */
	public function select($database)
	{
		if (!$database)
		{
			return false;
		}

		if (!sqlsrv_query($this->connection, 'USE ' . $database, null, array('scrollable' => SQLSRV_CURSOR_STATIC)))
		{

			$this->errorNum = 3;
			$this->errorMsg = JText::_('Could not connect to database');
			return false;
		}

		return true;
	}

	/**
	 * Set the connection to use UTF-8 character encoding.
	 *
	 * @return  boolean  True on success.
	 */
	public function setUTF()
	{
		// TODO: Remove this?
	}

	/**
	 * Method to commit a transaction.
	 *
	 * @return  void
	 */
	public function transactionCommit()
	{
		$this->setQuery('COMMIT TRANSACTION');
		$this->query();
	}

	/**
	 * Method to roll back a transaction.
	 *
	 * @return  void
	 */
	public function transactionRollback()
	{
		$this->setQuery('ROLLBACK TRANSACTION');
		$this->query();
	}

	/**
	 * Method to initialize a transaction.
	 *
	 * @return  void
	 */
	public function transactionStart()
	{
		$this->setQuery('START TRANSACTION');
		$this->query();
	}

	/**
	 * Method to fetch a row from the result set cursor as an array.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	protected function fetchArray($cursor = null)
	{
		return sqlsrv_fetch_array($cursor ? $cursor : $this->cursor, SQLSRV_FETCH_NUMERIC);
	}

	/**
	 * Method to fetch a row from the result set cursor as an associative array.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  mixed  Either the next row from the result set or false if there are no more rows.
	 */
	protected function fetchAssoc($cursor = null)
	{
		return sqlsrv_fetch_array($cursor ? $cursor : $this->cursor, SQLSRV_FETCH_ASSOC);
	}

	/**
	 * Method to fetch a row from the result set cursor as an object.
	 *
	 * @param   mixed   $cursor  The optional result set cursor from which to fetch the row.
	 * @param   string  $class   The class name to use for the returned row object.
	 *
	 * @return  mixed   Either the next row from the result set or false if there are no more rows.
	 */
	protected function fetchObject($cursor = null, $class = 'stdClass')
	{
		return sqlsrv_fetch_object($cursor ? $cursor : $this->cursor, $class);
	}

	/**
	 * Method to free up the memory used for the result set.
	 *
	 * @param   mixed  $cursor  The optional result set cursor from which to fetch the row.
	 *
	 * @return  void
	 */
	protected function freeResult($cursor = null)
	{
		sqlsrv_free_stmt($cursor ? $cursor : $this->cursor);
	}

	/**
	 * Method to check and see if a field exists in a table.
	 *
	 * @param   string  $table  The table in which to verify the field.
	 * @param   string  $field  The field to verify.
	 *
	 * @return  boolean  True if the field exists in the table.
	 */
	protected function checkFieldExists($table, $field)
	{
		$table = $this->replacePrefix((string) $table);
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS" . " WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$field'" .
			" ORDER BY ORDINAL_POSITION";
		$this->setQuery($sql);

		if ($this->loadResult())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to wrap an SQL statement to provide a LIMIT and OFFSET behavior for scrolling through a result set.
	 *
	 * @param   string   $sql     The SQL statement to process.
	 * @param   integer  $limit   The maximum affected rows to set.
	 * @param   integer  $offset  The affected row offset to set.
	 *
	 * @return  string   The processed SQL statement.
	 */
	protected function limit($sql, $limit, $offset)
	{
		$orderBy = stristr($sql, 'ORDER BY');
		if (is_null($orderBy) || empty($orderBy))
		{
			$orderBy = 'ORDER BY (select 0)';
		}
		$sql = str_ireplace($orderBy, '', $sql);

		$rowNumberText = ',ROW_NUMBER() OVER (' . $orderBy . ') AS RowNumber FROM ';

		$sql = preg_replace('/\\s+FROM/', '\\1 ' . $rowNumberText . ' ', $sql, 1);
		$sql = 'SELECT TOP ' . $this->limit . ' * FROM (' . $sql . ') _myResults WHERE RowNumber > ' . $this->offset;

		return $sql;
	}

	/**
	 * Renames a table in the database.
	 *
	 * @param   string  $oldTable  The name of the table to be renamed
	 * @param   string  $newTable  The new name for the table.
	 * @param   string  $backup    Table prefix
	 * @param   string  $prefix    For the table - used to rename constraints in non-mysql databases
	 *
	 * @return  JDatabase  Returns this object to support chaining.
	 */
	public function renameTable($oldTable, $newTable, $backup = null, $prefix = null)
	{
		$constraints = array();

		if (!is_null($prefix) && !is_null($backup))
		{
			$constraints = $this->getTableConstraints($oldTable);
		}
		if (!empty($constraints))
		{
			$this->renameConstraints($constraints, $prefix, $backup);
		}

		$this->setQuery("sp_rename '" . $oldTable . "', '" . $newTable . "'");

		return $this->query();
	}

	/**
	 * Locks a table in the database.
	 *
	 * @param   string  $tableName  The name of the table to lock.
	 *
	 * @return  JDatabase  Returns this object to support chaining.
	 */
	public function lockTable($tableName)
	{
		return $this;
	}

	/**
	 * Unlocks tables in the database.
	 *
	 * @return  JDatabase  Returns this object to support chaining.
	 */
	public function unlockTables()
	{
		return $this;
	}
}