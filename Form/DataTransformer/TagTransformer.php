<?php
namespace Propel\TaggableBehaviorBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * @author Arkadiusz Dzięgiel
 *
 */
class TagTransformer implements DataTransformerInterface {

	public function transform($tags)
	{
		if(empty($tags)) return null;
		
		$ret=array();
		if($tags instanceof \PropelCollection || is_array($tags))
			foreach($tags as $tag)
				$ret[] = $tag->getName();
		
		return implode(',', $ret);
	}

    public function reverseTransform($tags){
    	if(empty($tags)) return array();
    	return $tags;
    }

}
