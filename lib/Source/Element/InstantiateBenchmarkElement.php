<?php

namespace PhpBench\Source\Element;

class InstantiateBenchmarkElement extends Element
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('class', null);
        $resolver->setAllowedTypes('class', [ 'null', 'string' ]);
    }

    public function getLeftLines(array $options): array
    {
        return [
            sprintf('$class = new %s', $options['class'])
        ];
    }
}
