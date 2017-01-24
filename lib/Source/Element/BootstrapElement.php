<?php

namespace PhpBench\Source\Element;

class BootstrapElement extends Element
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('bootstrap', null);
        $resolver->setAllowedTypes('bootstrap', [ 'null', 'string' ]);
    }

    public function getLeftLines(array $options): array
    {
        return [
            sprintf('$bootstrap = "%s"', $options['bootstrap'])
        ];
    }
}
