<?php

namespace PhpBench\Source;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface CodeElementInterface
{
    /**
     * Return an array of lines of code which will be used in the template.
     */
    public function getLeftLines(array $options): array;

    /**
     * Return an array of lines of code which will be used in the template.
     */
    public function getRightLines(array $options): array;

    /**
     * Configure options.
     */
    public function configureOptions(OptionsResolver $resolver);
}
