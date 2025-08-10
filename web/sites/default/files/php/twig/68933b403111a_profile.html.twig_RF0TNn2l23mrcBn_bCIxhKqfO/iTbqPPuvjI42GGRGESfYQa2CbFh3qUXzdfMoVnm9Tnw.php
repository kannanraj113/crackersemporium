<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* modules/contrib/profile/templates/profile.html.twig */
class __TwigTemplate_54e47a304f935771f931aa16ddf2473b extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 26
        $context["classes"] = ["profile", ("profile--" . CoreExtension::getAttribute($this->env, $this->source,         // line 28
($context["profile"] ?? null), "id", [], "any", false, false, true, 28)), ("profile--type--" . \Drupal\Component\Utility\Html::getClass(CoreExtension::getAttribute($this->env, $this->source,         // line 29
($context["profile"] ?? null), "bundle", [], "any", false, false, true, 29))), ((CoreExtension::getAttribute($this->env, $this->source,         // line 30
($context["profile"] ?? null), "isDefault", [], "method", false, false, true, 30)) ? ("profile--is-default") : ("")), ((        // line 31
($context["view_mode"] ?? null)) ? (("profile--view-mode--" . \Drupal\Component\Utility\Html::getClass(($context["view_mode"] ?? null)))) : ("")), "clearfix"];
        // line 35
        yield "<div";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [($context["classes"] ?? null)], "method", false, false, true, 35), "html", null, true);
        yield ">
  ";
        // line 36
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["title_suffix"] ?? null), "contextual_links", [], "any", false, false, true, 36), "html", null, true);
        yield "
  ";
        // line 37
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["content"] ?? null), "html", null, true);
        yield "
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["profile", "view_mode", "attributes", "title_suffix", "content"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/profile/templates/profile.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  59 => 37,  55 => 36,  50 => 35,  48 => 31,  47 => 30,  46 => 29,  45 => 28,  44 => 26,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/profile/templates/profile.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/modules/contrib/profile/templates/profile.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 26];
        static $filters = ["clean_class" => 29, "escape" => 35];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set'],
                ['clean_class', 'escape'],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
