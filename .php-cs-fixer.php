<?php

use PhpCsFixer\Config as PhpCsFixerConfig;

class Config extends PhpCsFixerConfig
{
    public function __construct()
    {
        parent::__construct();

        $this->setRiskyAllowed(true);
    }

    public function getRules(): array
    {
        return [
            '@PSR2' => true,
            '@PHP71Migration' => true,
            'array_syntax' => ['syntax' => 'short'],
            'binary_operator_spaces' => [
                'default' => 'single_space',
            ],
            'blank_line_after_opening_tag' => true,
            'blank_line_after_namespace' => true,
            'blank_line_before_statement' => ['statements' => ['return']],
//            'braces' => true,
            'braces_position' => true,
            'cast_spaces' => true,
            'class_attributes_separation' => ['elements' => ['method' => 'one']],
            'class_definition' => true,
            'combine_consecutive_unsets' => true,
            'concat_space' => false,
            'constant_case' => ['case' => 'lower'],
            'control_structure_braces' => true,
            'control_structure_continuation_position' => true,
            'declare_strict_types' => true,
            'declare_parentheses' => true,
            'echo_tag_syntax' => true,
            'elseif' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'function_declaration' => true,
//            'function_typehint_space' => true,
            'header_comment' => false,
            'include' => true,
            'indentation_type' => true,
            'linebreak_after_opening_tag' => true,
            'line_ending' => true,
            'lowercase_keywords' => true,
            'method_argument_space' => true,
            'modernize_types_casting' => true,
            'native_function_casing' => true,
//            'new_with_braces' => true,
            'new_with_parentheses' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_closing_tag' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_multiple_statements_per_line' => true,
            'multiline_whitespace_before_semicolons' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
//            'no_trailing_comma_in_list_call' => true,
//            'no_trailing_comma_in_singleline_array' => true,
            'no_trailing_comma_in_singleline' => true,
            'no_trailing_whitespace_in_comment' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unreachable_default_argument_value' => true,
            'no_unused_imports' => true,
            'no_useless_else' => true,
            'no_useless_return' => true,
//            'no_spaces_inside_parenthesis' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'normalize_index_brace' => true,
            'not_operator_with_successor_space' => true,
            'object_operator_without_whitespace' => true,
            'ordered_imports' => true,
            'phpdoc_indent' => true,
            'general_phpdoc_tag_rename' => true,
            'phpdoc_inline_tag_normalizer' => true,
            'phpdoc_tag_type' => true,
            'psr_autoloading' => true,
            'return_type_declaration' => true,
            'semicolon_after_instruction' => true,
            'short_scalar_cast' => true,
            'simplified_null_return' => false,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => true,
            'single_import_per_statement' => true,
            'single_line_after_imports' => true,
            'single_line_comment_style' => ['comment_types' => ['hash']],
            'single_quote' => true,
            'single_space_around_construct' => true,
            'spaces_inside_parentheses' => true,
            'standardize_not_equals' => true,
            'statement_indentation' => true,
            'strict_comparison' => true,
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'ternary_operator_spaces' => true,
            'trailing_comma_in_multiline' => true,
            'trim_array_spaces' => true,
            'type_declaration_spaces' => true,
            'unary_operator_spaces' => true,
            'visibility_required' => true,
            'whitespace_after_comma_in_array' => true,
        ];
    }
}

$config = new Config();
$config->getFinder()->in(__DIR__)->exclude(['data', 'docs']);

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;
