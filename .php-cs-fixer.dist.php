<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true, // Используем стандарт PSR-12
        'phpdoc_align' => true, // Выравнивает комментарии PHPDoc
        'no_unused_imports'  => true, // Убирает неиспользуемые импорты.
        'ordered_imports' => ['sort_algorithm' => 'alpha'], //  Сортирует импорты в алфавитном порядке.
        'blank_line_before_statement' => [ // Добавляет пустую строку перед указанными операторами
            'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
        ],
        'trailing_comma_in_multiline' => true, // Добавляет или убирает запятую в конце списка многократных элементов
        'array_syntax' => ['syntax' => 'short'], // Используем короткую запись для массивов
        'declare_strict_types' => true, // Обязательное использование strict_types
    ])
    ->setFinder($finder)  // Указываем директорию для проверки
    ->setUsingCache(false) // Отключаем использование кэша
;
