<?php
namespace Smirik\TaggableBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 *
 * @author Arkadiusz Dzięgiel
 * @author Evgeny Smirnov <smirik@gmail.com>
 *
 */
class TagTransformer implements DataTransformerInterface {

	private $found_class = null;
	
	public function __construct($cls) 
	{
		$this->found_class = $cls;
	}
	
	public function transform($tags)
	{
		if (empty($tags))
		{
			return null;
		}

		$ret = array();
		if($tags instanceof \PropelCollection && is_null($this->found_class))
		{
			$this->found_class = $tags->getModel();
		}
		if($tags instanceof \PropelCollection || is_array($tags))
		{
			foreach($tags as $tag)
			{
				$ret[] = $tag->getName();
			}
		}
		return implode(',', $ret);
	}

	public function reverseTransform($tags)
	{
		if ($this->found_class!==null)
		{
			$cls = $this->found_class.'Query';

			$tags = explode(",",$tags);

			$ret = $cls::create()
				->filterByName($tags, \Criteria::IN)
				->find();

			foreach($tags as $tag)
			{
				$found = false;
				foreach($ret as $obj)
				{
					if ($obj->getName() == $tag)
					{
						$found = true;
						break;
					}
				}
				if (!$found)
				{
					$cls = $this->found_class;
					$o = new $cls();
					$o->setName(trim($tag));
					$ret[] = $o;
				}
			}
			return $ret;
		}

		if (empty($tags))
		{
			return array();
		}
		return $tags;
	}

}
