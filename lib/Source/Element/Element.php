<?php

namespace PhpBench\Source\Element;

use PhpBench\Source\CodeElementInterface;

class Element implements CodeElementInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLeftLines(array $options)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRightLines(array $options): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
