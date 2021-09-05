<?php

$header = <<<'EOF'
This file is part of CLI Executor.

(c) Dariusz Rumiński <dariusz.ruminski@gmail.com>

This source file is subject to the MIT license that is bundled
with this source code in the file LICENSE.
EOF;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(array(
      '@PHP71Migration' => true,
      '@PHP71Migration:risky' => true,
      '@PHPUnit75Migration:risky' => true,
      '@PhpCsFixer' => true,
      '@PhpCsFixer:risky' => true,
      // 'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']], // one should use PHPUnit built-in method instead
      'header_comment' => ['header' => $header],
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;
