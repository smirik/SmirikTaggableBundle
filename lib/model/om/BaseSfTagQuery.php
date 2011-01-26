<?php


/**
 * Base class that represents a query for the 'sf_tag' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.5.3 on:
 *
 * Wed Jan 26 10:02:07 2011
 *
 * @method     SfTagQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     SfTagQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     SfTagQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     SfTagQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     SfTagQuery groupById() Group by the id column
 * @method     SfTagQuery groupByName() Group by the name column
 * @method     SfTagQuery groupByCreatedAt() Group by the created_at column
 * @method     SfTagQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     SfTagQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     SfTagQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     SfTagQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     SfTagQuery leftJoinSfTagging($relationAlias = null) Adds a LEFT JOIN clause to the query using the SfTagging relation
 * @method     SfTagQuery rightJoinSfTagging($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SfTagging relation
 * @method     SfTagQuery innerJoinSfTagging($relationAlias = null) Adds a INNER JOIN clause to the query using the SfTagging relation
 *
 * @method     SfTag findOne(PropelPDO $con = null) Return the first SfTag matching the query
 * @method     SfTag findOneOrCreate(PropelPDO $con = null) Return the first SfTag matching the query, or a new SfTag object populated from the query conditions when no match is found
 *
 * @method     SfTag findOneById(int $id) Return the first SfTag filtered by the id column
 * @method     SfTag findOneByName(string $name) Return the first SfTag filtered by the name column
 * @method     SfTag findOneByCreatedAt(string $created_at) Return the first SfTag filtered by the created_at column
 * @method     SfTag findOneByUpdatedAt(string $updated_at) Return the first SfTag filtered by the updated_at column
 *
 * @method     array findById(int $id) Return SfTag objects filtered by the id column
 * @method     array findByName(string $name) Return SfTag objects filtered by the name column
 * @method     array findByCreatedAt(string $created_at) Return SfTag objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return SfTag objects filtered by the updated_at column
 *
 * @package    propel.generator.plugins.sfPropel15TaggableBehaviorPlugin.lib.model.om
 */
abstract class BaseSfTagQuery extends ModelCriteria
{

  /**
   * Initializes internal state of BaseSfTagQuery object.
   *
   * @param     string $dbName The dabase name
   * @param     string $modelName The phpName of a model, e.g. 'Book'
   * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
   */
  public function __construct($dbName = 'propel', $modelName = 'SfTag', $modelAlias = null)
  {
    parent::__construct($dbName, $modelName, $modelAlias);
  }

  /**
   * Returns a new SfTagQuery object.
   *
   * @param     string $modelAlias The alias of a model in the query
   * @param     Criteria $criteria Optional Criteria to build the query from
   *
   * @return    SfTagQuery
   */
  public static function create($modelAlias = null, $criteria = null)
  {
    if ($criteria instanceof SfTagQuery)
    {
      return $criteria;
    }
    $query = new SfTagQuery();
    if (null !== $modelAlias)
    {
      $query->setModelAlias($modelAlias);
    }
    if ($criteria instanceof Criteria)
    {
      $query->mergeWith($criteria);
    }
    return $query;
  }

  /**
   * Find object by primary key
   * Use instance pooling to avoid a database query if the object exists
   * <code>
   * $obj  = $c->findPk(12, $con);
   * </code>
   * @param     mixed $key Primary key to use for the query
   * @param     PropelPDO $con an optional connection object
   *
   * @return    SfTag|array|mixed the result, formatted by the current formatter
   */
  public function findPk($key, $con = null)
  {
    if ((null !== ($obj = SfTagPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter())
    {
      // the object is alredy in the instance pool
      return $obj;
    }
    else
    {
      // the object has not been requested yet, or the formatter is not an object formatter
      $criteria = $this->isKeepQuery() ? clone $this : $this;
      $stmt = $criteria
        ->filterByPrimaryKey($key)
        ->getSelectStatement($con);
      return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }
  }

  /**
   * Find objects by primary key
   * <code>
   * $objs = $c->findPks(array(12, 56, 832), $con);
   * </code>
   * @param     array $keys Primary keys to use for the query
   * @param     PropelPDO $con an optional connection object
   *
   * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
   */
  public function findPks($keys, $con = null)
  {  
    $criteria = $this->isKeepQuery() ? clone $this : $this;
    return $this
      ->filterByPrimaryKeys($keys)
      ->find($con);
  }

  /**
   * Filter the query by primary key
   *
   * @param     mixed $key Primary key to use for the query
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterByPrimaryKey($key)
  {
    return $this->addUsingAlias(SfTagPeer::ID, $key, Criteria::EQUAL);
  }

  /**
   * Filter the query by a list of primary keys
   *
   * @param     array $keys The list of primary key to use for the query
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterByPrimaryKeys($keys)
  {
    return $this->addUsingAlias(SfTagPeer::ID, $keys, Criteria::IN);
  }

  /**
   * Filter the query on the id column
   * 
   * @param     int|array $id The value to use as filter.
   *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterById($id = null, $comparison = null)
  {
    if (is_array($id) && null === $comparison)
    {
      $comparison = Criteria::IN;
    }
    return $this->addUsingAlias(SfTagPeer::ID, $id, $comparison);
  }

  /**
   * Filter the query on the name column
   * 
   * @param     string $name The value to use as filter.
   *            Accepts wildcards (* and % trigger a LIKE)
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterByName($name = null, $comparison = null)
  {
    if (null === $comparison)
    {
      if (is_array($name))
      {
        $comparison = Criteria::IN;
      }
      elseif (preg_match('/[\%\*]/', $name))
      {
        $name = str_replace('*', '%', $name);
        $comparison = Criteria::LIKE;
      }
    }
    return $this->addUsingAlias(SfTagPeer::NAME, $name, $comparison);
  }

  /**
   * Filter the query on the created_at column
   * 
   * @param     string|array $createdAt The value to use as filter.
   *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterByCreatedAt($createdAt = null, $comparison = null)
  {
    if (is_array($createdAt))
    {
      $useMinMax = false;
      if (isset($createdAt['min']))
      {
        $this->addUsingAlias(SfTagPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
        $useMinMax = true;
      }
      if (isset($createdAt['max']))
      {
        $this->addUsingAlias(SfTagPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
        $useMinMax = true;
      }
      if ($useMinMax)
      {
        return $this;
      }
      if (null === $comparison)
      {
        $comparison = Criteria::IN;
      }
    }
    return $this->addUsingAlias(SfTagPeer::CREATED_AT, $createdAt, $comparison);
  }

  /**
   * Filter the query on the updated_at column
   * 
   * @param     string|array $updatedAt The value to use as filter.
   *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterByUpdatedAt($updatedAt = null, $comparison = null)
  {
    if (is_array($updatedAt))
    {
      $useMinMax = false;
      if (isset($updatedAt['min']))
      {
        $this->addUsingAlias(SfTagPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
        $useMinMax = true;
      }
      if (isset($updatedAt['max']))
      {
        $this->addUsingAlias(SfTagPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
        $useMinMax = true;
      }
      if ($useMinMax)
      {
        return $this;
      }
      if (null === $comparison)
      {
        $comparison = Criteria::IN;
      }
    }
    return $this->addUsingAlias(SfTagPeer::UPDATED_AT, $updatedAt, $comparison);
  }

  /**
   * Filter the query by a related SfTagging object
   *
   * @param     SfTagging $sfTagging  the related object to use as filter
   * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function filterBySfTagging($sfTagging, $comparison = null)
  {
    return $this
      ->addUsingAlias(SfTagPeer::ID, $sfTagging->getTagId(), $comparison);
  }

  /**
   * Adds a JOIN clause to the query using the SfTagging relation
   * 
   * @param     string $relationAlias optional alias for the relation
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function joinSfTagging($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
  {
    $tableMap = $this->getTableMap();
    $relationMap = $tableMap->getRelation('SfTagging');
    
    // create a ModelJoin object for this join
    $join = new ModelJoin();
    $join->setJoinType($joinType);
    $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
    if ($previousJoin = $this->getPreviousJoin())
    {
      $join->setPreviousJoin($previousJoin);
    }
    
    // add the ModelJoin to the current object
    if($relationAlias)
    {
      $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
      $this->addJoinObject($join, $relationAlias);
    }
    else
    {
      $this->addJoinObject($join, 'SfTagging');
    }
    
    return $this;
  }

  /**
   * Use the SfTagging relation SfTagging object
   *
   * @see       useQuery()
   * 
   * @param     string $relationAlias optional alias for the relation,
   *                                   to be used as main alias in the secondary query
   * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
   *
   * @return    SfTaggingQuery A secondary query class using the current class as primary query
   */
  public function useSfTaggingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
  {
    return $this
      ->joinSfTagging($relationAlias, $joinType)
      ->useQuery($relationAlias ? $relationAlias : 'SfTagging', 'SfTaggingQuery');
  }

  /**
   * Exclude object from result
   *
   * @param     SfTag $sfTag Object to remove from the list of results
   *
   * @return    SfTagQuery The current query, for fluid interface
   */
  public function prune($sfTag = null)
  {
    if ($sfTag)
    {
      $this->addUsingAlias(SfTagPeer::ID, $sfTag->getId(), Criteria::NOT_EQUAL);
    }
    
    return $this;
  }

  // timestampable behavior
  
  /**
   * Filter by the latest updated
   *
   * @param      int $nbDays Maximum age of the latest update in days
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function recentlyUpdated($nbDays = 7)
  {
    return $this->addUsingAlias(SfTagPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
  }
  
  /**
   * Filter by the latest created
   *
   * @param      int $nbDays Maximum age of in days
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function recentlyCreated($nbDays = 7)
  {
    return $this->addUsingAlias(SfTagPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
  }
  
  /**
   * Order by update date desc
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function lastUpdatedFirst()
  {
    return $this->addDescendingOrderByColumn(SfTagPeer::UPDATED_AT);
  }
  
  /**
   * Order by update date asc
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function firstUpdatedFirst()
  {
    return $this->addAscendingOrderByColumn(SfTagPeer::UPDATED_AT);
  }
  
  /**
   * Order by create date desc
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function lastCreatedFirst()
  {
    return $this->addDescendingOrderByColumn(SfTagPeer::CREATED_AT);
  }
  
  /**
   * Order by create date asc
   *
   * @return     SfTagQuery The current query, for fuid interface
   */
  public function firstCreatedFirst()
  {
    return $this->addAscendingOrderByColumn(SfTagPeer::CREATED_AT);
  }

}
