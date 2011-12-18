---------------------------------
Propel\TaggableBehaviorBundle
---------------------------------

A behavior and a widget for symfony 2.x and propel 1.6



How to install
--------------

- add this plugin as a git submodule in your project. From your project root:

    git submodule add https://bitbucket.org/glorpen/taggablebehaviorbundle.git Propel/TaggableBehaviorBundle

- enable the plugin in your **AppKernel** class

*app/AppKernel.php*

::

    <?php

    class AppKernel extends AppKernel
    {
        public function registerBundles()
        {
            $bundles = array(
            	...
            	new Propel\TaggableBehaviorBundle\TaggableBehaviorBundle(),
            	...
            );
        }
    }

- add the **taggable** behavior to a class in your schema file

*config/schema.xml*

::

    <table name="article">
        <behavior name="taggable" />
        <column name="id" type="integer" primaryKey="true" autoIncrement="true"/>
        <column name="title" type="varchar" size="255" />
        <!-- ... -->
    </table>

- rebuild your model

::

    app/console propel:build-all

- publish assets

::

    php symfony plugin:publish-assets


Classes And Tables generated
----------------------------

The behavior creates a **taggable_tag** table that is populated with tags
Then it creates a **%table%_tagging table** for every object in your model with the taggable behavior.
This middle table is marked as **isCrossRef**, with two foreign keys, one on the object and one on the tag table.
This integrates the tagging mechanism completely inside propel

How to use
----------

Some examples:

::

    <?php
    $article = new Article();


    // there are two ways to add tags. The propel way:
    $tag = new Tag();
    $tag->setName('propel');
    $article->addTag($tag);
    $article->save();
    
    // or the addTags method, that directly accept strings, array or csv
    $article->addTags('symfony'); // a string with no comma is a single tag
    $article->addTags('linux, ubuntu'); // a string with comma is multiple tag
    $article->addTags('symfony'); // if the object is already tagged nothing happens
    $article->addTags(array('linus', 'torvalds')); // list of tags as an array


    // remove tags
    $article->removeTags('symfony');
    $article->removeTags('linux, ubuntu');
    $article->removeTags(array('linus', 'torvalds'));

    // retrieve tags
    $article->getTags() // PropelCollection of Tag object


    // Query object
    $articles = ArticleQuery::create()->filterByTagName('propel')->find();

    // you could also use the propel generated method. filterByTagName is just a shortcut of
    $articles = ArticleQuery::create()->useArticleTaggingQuery()->useTagQuery()->filterByName('propel')->endUse()->endUse();

    // if you have a tag object (for example in a list of article tagged with...) propel has already done the dirty job
    $tag = TagQuery::create()->findOneByName('symfony');
    $articles = ArticleQuery::create()->filterByTag($tag)->find();
    

As widget in forms

::

	<?php
	namespace Glorpen\GalleryBundle\Form\Type;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilder;
	
	class GalleryType extends AbstractType
	{
		public function getDefaultOptions(array $options)
		{
			return array(
				'data_class' => 'Glorpen\GalleryBundle\Model\Gallery',
			);
		}
	
		public function buildForm(FormBuilder $builder, array $options)
		{
			$builder->add('title', 'text', array('label'=>'Title'));
			$builder->add('tags', 'tags', array('label' => 'Tags', 'defaultText'=>'add tag'));
		}
	
		public function getName(){
			return 'gallery';
		}
	
	}
