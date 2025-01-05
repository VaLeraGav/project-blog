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

        'increment_style' => false, // Отключить изменение стиля инкремента
        'concat_space' => ['spacing' => 'one'], // пробел между '.'
        'phpdoc_align' => true, // Выравнивает комментарии PHPDoc
        'full_opening_tag' => true, // PHP-код должен использовать длинные теги <?php

        // Вставьте фигурные скобки после new; например, замените "$x = new X" на "$x = new X()".
        'new_with_braces' => true,

        'no_unused_imports' => true, // Убирает неиспользуемые импорты.
        'ordered_imports' => ['sort_algorithm' => 'alpha'], //  Сортирует импорты в алфавитном порядке.

        'trailing_comma_in_multiline' => true, // Добавляет или убирает запятую в конце списка многократных элементов
        'array_syntax' => ['syntax' => 'short'], // Используем короткую запись для массивов
        'declare_strict_types' => true, // Обязательное использование strict_types

        // Удаляет начальную часть полностью квалифицированных ссылок на символы, если данный символ импортирован или принадлежит текущему пространству имен.
        'fully_qualified_strict_types' => true,
        // Импортирует глобальные классы/функции/константы
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true
        ],

        'blank_line_after_namespace' => true, // После объявления пространства имен должна быть одна пустая строка.
        'blank_line_before_statement' => false,  // Добавляет пустую строку перед определёнными конструкциями
//        'blank_line_before_statement' => [ // Добавляет пустую строку перед указанными операторами
//            'statements' => ['declare', 'return', 'throw', 'try'],
//        ],
    ])
    ->setFinder($finder)  // Указываем директорию для проверки
    ->setUsingCache(false) // Отключаем использование кэша
    ;
