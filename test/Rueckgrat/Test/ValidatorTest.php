<?php

namespace Rueckgrat\Test;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    public function testAddMessage()
    {

        $validator = $this->getMockForAbstractClass('\\Tuersteher\\Validator');
        $message1 = 'This is message1';
        $validator->addMessage('message1', $message1);
        $message2 = 'This is message2';
        $validator->addMessage('message2', $message2);
        $this->assertEquals($validator->getMessage('message1'), $message1);
        $this->assertEquals($validator->getMessage('message2'), $message2);
        $this->setExpectedException('\\Tuersteher\\Exception\\Validator');
        $validator->addMessage('message1', $message2);

    }
    
}
