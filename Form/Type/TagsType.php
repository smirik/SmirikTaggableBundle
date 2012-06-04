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
		return array(
			'autocomplete_url' => null,
			'autocomplete' => array(),
			'height' => '100px',
			'width' => '300px',
			'interactive' => true,
			'defaultText' => 'add a tag',
			'removeWithBackspace' => true,
			'minChars' => 0,
			'maxChars' => 255, //if not provided there is no limit,
			'placeholderColor' => '#666666',
			'class' => null,
		);
	}
	
	public function buildForm(FormBuilder $builder, array $options)
	{
		parent::buildForm($builder, $options);
		
		$builder->setAttribute('tags-config', array_intersect_key($options, $this->getDefaultOptions(array())));
		$builder->prependClientTransformer(new TagTransformer($options['class']));
	}
	
	public function buildView(FormView $view, FormInterface $form)
	{
		$view->set('config', $form->getAttribute('tags-config'));
	}
}
