<?php

namespace PhpBench\Benchmark\Executor;

use PhpBench\Benchmark\ExecutorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PhpBench\Benchmark\Metadata\SubjectMetadata;
use PhpBench\Model\Iteration;
use PhpBench\Registry\Config;
use PhpBench\Benchmark\Metadata\BenchmarkMetadata;

class DynamicExecutor implements ExecutorInterface
{

    /**
     * {@inheritDoc}
     */
    public function configure(OptionsResolver $options)
    {
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function execute(SubjectMetadata $subjectMetadata, Iteration $iteration, Config $config)
    {
        $tokens = [
            'class' => $subjectMetadata->getBenchmark()->getClass(),
            'file' => $subjectMetadata->getBenchmark()->getPath(),
            'subject' => $subjectMetadata->getName(),
            'revolutions' => $iteration->getVariant()->getRevolutions(),
            'beforeMethods' => var_export($subjectMetadata->getBeforeMethods(), true),
            'afterMethods' => var_export($subjectMetadata->getAfterMethods(), true),
            'parameters' => var_export($iteration->getVariant()->getParameterSet()->getArrayCopy(), true),
            'warmup' => $iteration->getVariant()->getWarmup() ?: 0,
        ];

        $template = $this->templateBuilderFactory->create()
            ->add(new Header())
            ->add(new Warmup())
            ->add(new Collector\Microtime(
                new Revolver()
            ))
            ->getTemplate($tokens);

        $this->launcher->launch($template->getBody());
    }
    
    
    /**
     * {@inheritDoc}
     */
    public function executeMethods(BenchmarkMetadata $benchmark, array $methods)
    {
        $template = $this->templateBuilderFactory->create()
            ->add(new Code\Environment())
            ->add(new Code\Bootstrap())
            ->add(new Code\Methods())
            ->getTemplate();
    }
}
