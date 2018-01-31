<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class DbConnection extends CDbConnection {

    /**
     * Use sql cache or not
     * @var boolean 
     */
    public $useCache = false;
    
    /**
     *
     * @var boolean use fulltext  inedex
     */
    public $useFullText = false;

    /**
     * Creates a command for execution.
     * @param mixed $query the DB query to be executed. This can be either a string representing a SQL statement,     
     * or an array representing different fragments of a SQL statement. Please refer to {@link CDbCommand::__construct}
     * @param boolean $useCace use cache for this query or not.
     * @param CCacheDependency $dependency the dependency that will be used when saving the query results into cache.
     * for more details about how to pass an array as the query. If this parameter is not given,
     * you will have to call query builder methods of {@link CDbCommand} to build the DB query.
     * @return CDbCommand the DB command
     */
    public function createCommand($query = null, $useCace = false, $dependency = null) {
        if ($this->useCache && $useCace && $cache = Yii::app()->getComponent($this->queryCacheID) !== null) {
            $this->cache($this->queryCachingDuration, $dependency);
        }
        return parent::createCommand($query);
    }

    /**
     * Sets the parameters about query caching.
     * This method can be used to enable or disable query caching.
     * By setting the $duration parameter to be 0, the query caching will be disabled.
     * Otherwise, query results of the new SQL statements executed next will be saved in cache
     * and remain valid for the specified duration.
     * If the same query is executed again, the result may be fetched from cache directly
     * without actually executing the SQL statement.
     * @param integer $duration the number of seconds that query results may remain valid in cache.
     * If $duration == -1 then use the default cache duration 
     * If this is 0, the caching will be disabled.
     * @param CCacheDependency $dependency the dependency that will be used when saving the query results into cache.
     * @param integer $queryCount number of SQL queries that need to be cached after calling this method. Defaults to 1,
     * meaning that the next SQL query will be cached.
     * @return CDbConnection the connection instance itself.
     * @since 1.1.7
     */
    public function cache($duration = -1, $dependency=null, $queryCount=1) {
        if($duration != -1){
            $this->queryCachingDuration = $duration;
        }
        $this->queryCachingDependency = $dependency;
        $this->queryCachingCount = $queryCount;
        return $this;
    }

}

?>
