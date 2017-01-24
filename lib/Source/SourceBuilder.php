<?php

namespace PhpBench\Source;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceBuilder
{
    private $elements = [];

    public function add(CodeElementInterface $code)
    {
        $this->elements[] = $code;
    }

    public function build(array $options): string
    {
        $resolver = new OptionsResolver();

        foreach ($this->elements as $element) {
            $element->configureOptions($resolver);
        }

        $options = $resolver->resolve($options);

        $lines = [];
        foreach ($this->elements as $element) {
            $line = implode(PHP_EOL, $element->getLeftLines($options));

            if ($line) {
                $lines[] = $line;
            }
        }

        foreach (array_reverse($this->elements) as $element) {
            $line = implode(PHP_EOL, $element->getRightLines($options));

            if ($line) {
                $lines[] = $line;
            }
        }

        return implode(PHP_EOL, $lines);
    }
}
