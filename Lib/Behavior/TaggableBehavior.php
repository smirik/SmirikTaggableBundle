<?php

/*
 *  matteosister <matteog@gmail.com>
 *  Just for fun...
 */

class TaggableBehavior extends Behavior
{
    protected $parameters = array(
        'tagging_table' => '%TABLE%_tagging',
        'tagging_table_phpname' => '%PHPNAME%Tagging',
        'tagging_table_tag_id' => 'tag_id',
        'tagging_table_taggable_id' => '%TABLE%_id',
        'tag_table' => 'taggable_tag',
        'tag_table_phpname' => 'Tag',
        'tag_table_id' => 'id',
        'tag_table_tag_name' => 'name',
        'upperCase' => false,
    );

    protected $taggingTable,
        $tagTable,
        $objectBuilderModifier,
        $queryBuilderModifier,
        $peerBuilderModifier;

    public function modifyTable()
    {
        $this->createTagTable();
        $this->createTaggingTable();
    }

    protected function createTagTable()
    {
        $table = $this->getTable();
        $database = $table->getDatabase();

        $tagTableName = $this->get('tag_table');

        if ($database->hasTable($tagTableName)) {
            $this->tagTable = $database->getTable($tagTableName);
        } else {
            $this->tagTable = $database->addTable(array(
                'name'      => $tagTableName,
                'phpName'   => $this->get('tag_table_phpname'),
                'package'   => $table->getPackage(),
                'schema'    => $table->getSchema(),
                'namespace' => '\\'.$table->getNamespace(),
            ));

            // every behavior adding a table should re-execute database behaviors
            // see bug 2188 http://www.propelorm.org/changeset/2188
            foreach ($database->getBehaviors() as $behavior) {
                $behavior->modifyDatabase();
            }
        }

        $tagTableIdColumn = $this->get('tag_table_id');
        if (!$this->tagTable->hasColumn($tagTableIdColumn)) {
            $this->tagTable->addColumn(array(
                'name'          => $tagTableIdColumn,
                'type'          => PropelTypes::INTEGER,
                'primaryKey'    => 'true',
                'autoIncrement' => 'true',
            ));
        }

        $tagTableNameColumn = $this->get('tag_table_tag_name');
        if (!$this->tagTable->hasColumn($tagTableNameColumn)) {
            $this->tagTable->addColumn(array(
                'name'          => $tagTableNameColumn,
                'type'          => PropelTypes::VARCHAR,
                'size'          => '60',
                'primaryString' => 'true'
            ));
        }
    }

    protected function createTaggingTable()
    {
        $table = $this->getTable();
        $database = $table->getDatabase();

        $pks = $this->getTable()->getPrimaryKey();
        if (count($pks) > 1) {
            throw new EngineException('The Taggable behavior does not support tables with composite primary keys');
        }
        $pk = array_shift($pks);

        $taggingTableName = $this->get('tagging_table');

        if ($database->hasTable($taggingTableName)) {
            $this->taggingTable = $database->getTable($taggingTableName);
        } else {
            $this->taggingTable = $database->addTable(array(
                'name'      => $taggingTableName,
                'phpName'   => $this->get('tagging_table_phpname'),
                'package'   => $table->getPackage(),
                'schema'    => $table->getSchema(),
                'namespace' => '\\'.$table->getNamespace(),
            ));

            // every behavior adding a table should re-execute database behaviors
            // see bug 2188 http://www.propelorm.org/changeset/2188
            foreach ($database->getBehaviors() as $behavior) {
                $behavior->modifyDatabase();
            }
        }

        $taggingTableTaggingId = $this->get('tagging_table_taggable_id');
        if ($this->taggingTable->hasColumn($taggingTableTaggingId)) {
            $objFkColumn = $this->taggingTable->getColumn($taggingTableTaggingId);
        } else {
            $objFkColumn = $this->taggingTable->addColumn(array(
                'name'          => $taggingTableTaggingId,
                'type'          => PropelTypes::INTEGER,
                'primaryKey'    => 'true'
            ));
        }

        $taggingTableTagId = $this->get('tagging_table_tag_id');
        if ($this->taggingTable->hasColumn($taggingTableTagId)) {
            $tagFkColumn = $this->taggingTable->getColumn($taggingTableTagId);
        } else {
            $tagFkColumn = $this->taggingTable->addColumn(array(
                'name'          => $taggingTableTagId,
                'type'          => PropelTypes::INTEGER,
                'primaryKey'    => 'true'
            ));
        }

        $this->taggingTable->setIsCrossRef(true);

        // Add FK between table and taggingTable
        $fkObj = new ForeignKey();
        $fkObj->setForeignTableCommonName($this->getTable()->getCommonName());
        $fkObj->setForeignSchemaName($this->getTable()->getSchema());
        $fkObj->setOnDelete(ForeignKey::CASCADE);
        $fkObj->setOnUpdate(ForeignKey::CASCADE);
        $fkObj->addReference($objFkColumn->getName(), $pk->getName());
        $this->taggingTable->addForeignKey($fkObj);

        // Add FK between taggingTable and tagTable
        $fkTag = new ForeignKey();
        $fkTag->setForeignTableCommonName($this->tagTable->getCommonName());
        $fkTag->setForeignSchemaName($this->tagTable->getSchema());
        $fkTag->setOnDelete(ForeignKey::CASCADE);
        $fkTag->setOnUpdate(ForeignKey::CASCADE);
        $fkTag->addReference($tagFkColumn->getName(), $this->get('tag_table_id'));
        $this->taggingTable->addForeignKey($fkTag);
    }

    /**
     * Adds methods to the object
     */
    public function objectMethods($builder)
    {
        $this->builder = $builder;

        $script = '';

        $this->addAddTagsMethod($script);
        $this->addRemoveTagMethod($script);

        return $script;
    }

    private function addAddTagsMethod(&$script)
    {
        $table = $this->getTable();
        $script .= "

/**
 * Add tags
 * @param   array|string    \$tags A string for a single tag or an array of strings for multiple tags
 * @param   PropelPDO       \$con optional connection object
 */
public function addTags(\$tags, PropelPDO \$con = null)
{
    \$arrTags = is_string(\$tags) ? explode(',', \$tags) : \$tags;
        // Remove duplicate tags.
    \$arrTags = array_intersect_key(\$arrTags, array_unique(array_map('strtolower', \$arrTags)));
    foreach (\$arrTags as \$tag) {
        \$tag = trim(\$tag);
        if (\$tag == \"\") continue;
        \$theTag = {$this->tagTable->getPhpName()}Query::create()->filterByName(\$tag)->findOne();

        // if the tag do not already exists
        if (null === \$theTag) {
            // create the tag
            \$theTag = new {$this->tagTable->getPhpName()}();
            \$theTag->setName(\$tag);
            \$theTag->save(\$con);
        }
          // Add the tag **only** if not already associated
        \$found = false;
        \$coll = \$this->getTags(null, \$con);
        foreach (\$coll as \$t) {
            if (\$t->getId() == \$theTag->getId()) {
                \$found = true;
                break;
            }
        }
        if (!\$found) {
            \$this->addTag(\$theTag);
        }
    }
}

";
    }

    private function addRemoveTagMethod(&$script)
    {
        $table = new Table();
        $table = $this->getTable();

        $script .= "
/**
 * Remove a tag
 * @param   array|string    \$tags A string for a single tag or an array of strings for multiple tags
 */
public function removeTags(\$tags)
{
    \$arrTags = is_string(\$tags) ? explode(',', \$tags) : \$tags;
    foreach (\$arrTags as \$tag) {
        \$tag = trim(\$tag);
        \$tagObj = {$this->tagTable->getPhpName()}Query::create()->findOneByName(\$tag);
        if (null === \$tagObj) {
            return;
        }
        \$taggings = \$this->get{$this->taggingTable->getPhpName()}s();
        foreach (\$taggings as \$tagging) {
            if (\$tagging->get{$this->tagTable->getPhpName()}Id() == \$tagObj->getId()) {
                \$tagging->delete();
            }
        }
    }
}

/**
 * Remove all tags
 * @param      PropelPDO \$con optional connection object
 */
public function removeAllTags(PropelPDO \$con = null)
{
    // Get all tags for this object
    \$taggings = \$this->get{$this->taggingTable->getPhpName()}s(\$con);
    foreach (\$taggings as \$tagging) {
      \$tagging->delete(\$con);
    }
}

";
    }

    /**
     * Adds method to the query object
     */
    public function queryMethods($builder)
    {
        $this->builder = $builder;
        $script = '';

        $this->addFilterByTagName($script);

        return $script;
    }

    protected function addFilterByTagName(&$script)
    {
        $script .= "
/**
 * Filter the query on the tag name
 *
 * @param     string \$tagName A single tag name
 *
 * @return    " . $this->builder->getStubQueryBuilder()->getClassname() . " The current query, for fluid interface
 */
public function filterByTagName(\$tagName)
{
    return \$this->use".$this->taggingTable->getPhpName()."Query()->useTagQuery()->filterByName(\$tagName)->endUse()->endUse();
}
";
    }

    protected function get($attribute)
    {
        $attributeValue = $this->replaceTokens($this->getParameter($attribute));

        return !$this->isUpperCase() ? $attributeValue : strtoupper($attributeValue);
    }

    public function replaceTokens($string)
    {
        $table = $this->getTable();

        return strtr($string, array(
            '%TABLE%'   => $table->getName(),
            '%PHPNAME%' => $table->getPhpName(),
        ));
    }

    protected function isUpperCase()
    {
        return $this->getParameter('upperCase');
    }

    public function objectFilter(&$script)
    {
        $s = <<<EOF

        if (empty(\$tags)) {
            \$this->removeAllTags(\$con);

            return;
        }

        if (is_string(\$tags)) {
            \$tagNames = explode(',',\$tags);

            \$tags = TagQuery::create()
            ->filterByName(\$tagNames)
            ->find(\$con);

            \$existingTags = array();
            foreach(\$tags as \$t) \$existingTags[] = \$t->getName();
            foreach (array_diff(\$tagNames, \$existingTags) as \$t) {
                \$tag=new Tag();
                \$tag->setName(\$t);
                \$tags->append(\$tag);
            }
        }

EOF;
        $script = preg_replace('/(public function setTags\()PropelCollection ([^{]*{)/', '$1$2'.$s, $script, 1);
    }

}

