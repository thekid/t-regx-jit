<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Orthography;
use TRegx\CleanRegex\Internal\Prepared\Template\AlternationToken;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;
use TRegx\CleanRegex\Internal\Prepared\Template\MaskToken;
use TRegx\CleanRegex\Internal\Prepared\Template\Token;
use TRegx\CleanRegex\Pattern;

class TemplateBuilder
{
    /** @var Orthography */
    private $orthography;
    /** @var Token[] */
    private $tokens;

    public function __construct(Orthography $orthography, array $tokens)
    {
        $this->orthography = $orthography;
        $this->tokens = $tokens;
    }

    public function mask(string $mask, array $keywords): TemplateBuilder
    {
        return $this->next(new MaskToken($mask, $keywords));
    }

    public function literal(string $text): TemplateBuilder
    {
        return $this->next(new LiteralToken($text));
    }

    public function alteration(array $figures): TemplateBuilder
    {
        return $this->next(new AlternationToken($figures));
    }

    private function next(Token $token): TemplateBuilder
    {
        return new TemplateBuilder($this->orthography, \array_merge($this->tokens, [$token]));
    }

    public function build(): Pattern
    {
        return new Pattern(new Template($this->orthography, new TokenFigures($this->tokens)));
    }
}
