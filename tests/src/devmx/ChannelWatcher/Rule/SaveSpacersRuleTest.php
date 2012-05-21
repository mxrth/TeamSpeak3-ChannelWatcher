<?php

namespace devmx\ChannelWatcher\Rule;


/**
 * Test class for SaveSpacersRule.
 * Generated by PHPUnit on 2012-05-21 at 12:00:48.
 */
class SaveSpacersRuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers devmx\ChannelWatcher\Rule\SaveSpacersRule::filter
     * @todo Implement testFilter().
     */
    public function testFilter()
    {
        $rule = new SaveSpacersRule;
        $list = array(
          1 => array(
              'cid' => 1,
              'channel_name' => 'asdf',
              'pid' => 0,
              '__delete' => false,
          ),
          2 => array(
              'cid' => 2,
              'channel_name' => 'asdfg',
              'pid' => 0,
              '__delete' => true,
          ),
          3 => array(
              'cid' => 3,
              'channel_name' => '[spacer1]---',
              'pid' => 1,
              '__delete' => true,
          ),
          4 => array(
              'cid' => 4,
              'channel_name' => '[spacer1]---',
              'pid' => 0,
              '__delete' => true
          )
        );
        $expected = $list;
        $expected[4]['__delete'] = false;
        $this->assertEquals($expected, $rule->filter($list));
    }

}

?>
