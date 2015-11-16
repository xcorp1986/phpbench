<?php


/**
 * @Iterations(20)
 * @Revs(10000)
 */
class StringConcatenationBench
{
    private $hello = 'hello';
    private $world = 'world';

    public function benchDot()
    {
        $val = $this->hello . ' ' . $this->world;
    }

    public function benchInlineBrackets()
    {
        $hello = 'hello';
        $world = 'world';
        $val = "{$hello} {$world}";
    }

    public function benchInlineNoBrackets()
    {
        $hello = 'hello';
        $world = 'world';
        $val = "$hello $world";
    }

    public function benchSprintf()
    {
        $val = sprintf('%s %s', $this->hello, $this->world);
    }

    public function benchImplode()
    {
        $val = implode('', array($this->hello, ' ', $this->world));
    }
}
