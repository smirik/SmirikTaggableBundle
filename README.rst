---------------------------------
Propel\TaggableBehaviorBundle
---------------------------------

A behavior and a widget for symfony 2.1 and propel 1.6



How to install
--------------

- add this bundle to the composer.json

  {
    "require": {
      "smirik/taggable-bundle": "*"
    }
  }

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
            	new Smirik\TaggableBundle\SmirikTaggableBundle(),
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

    php app/console propel:build


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
			$builder->add('tags', 'tags', array('label' => 'Tags', 'defaultText'=>'add tag', 'class' => 'YOUR_TAG_CLASS_WITH_NAMESPACE'));
		}

		public function getName(){
			return 'gallery';
		}

	}
