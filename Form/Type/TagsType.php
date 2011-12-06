<?php
namespace Propel\TaggableBehaviorBundle\Form\Type;

use Propel\TaggableBehaviorBundle\Form\DataTransformer\TagTransformer;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\FormView;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\AbstractType;

class TagsType extends AbstractType {
	public function getParent(array $options)
	{
		return 'text';
	}
	
	public function getName()
	{
		return 'tags';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array();
	}
	
	public function buildForm(FormBuilder $builder, array $options)
	{
		parent::buildForm($builder, $options);
		 
		//$builder->setAttribute('tinymce-config', $conf);
		$builder->prependClientTransformer(new TagTransformer());
	}
	
/*	public function buildView(FormView $view, FormInterface $form)
	{
		//$view->set('config', $form->getAttribute('tinymce-config'));
	}*/
}
