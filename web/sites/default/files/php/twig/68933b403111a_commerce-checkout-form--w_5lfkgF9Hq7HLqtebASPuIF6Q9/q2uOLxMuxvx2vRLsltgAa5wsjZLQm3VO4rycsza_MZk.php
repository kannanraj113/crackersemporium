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

/* themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-form--with-sidebar.html.twig */
class __TwigTemplate_fbd8a7c8b9f24a89a1a2492cb6ad6fd3 extends Template
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
        // line 12
        yield "<div class=\"layout-checkout-form row mb-5\">
  <div class=\"layout-region layout-region-checkout-main col-lg-7\">
    ";
        // line 14
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter(($context["form"] ?? null), "sidebar", "actions"), "html", null, true);
        yield "
  </div>
  <div class=\"layout-region layout-region-checkout-secondary col-lg-5 mb-4\">
    <div class=\"bg-light p-4\">
      <h5 class=\"mb-3\">";
        // line 18
        yield t("Order Summary", array());
        yield "</h5>
      ";
        // line 19
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "sidebar", [], "any", false, false, true, 19), "html", null, true);
        yield "
    </div>
  </div>
  <div class=\"layout-region layout-region-checkout-footer col\">
    ";
        // line 23
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["form"] ?? null), "actions", [], "any", false, false, true, 23), "html", null, true);
        yield "
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["form"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-form--with-sidebar.html.twig";
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
        return array (  66 => 23,  59 => 19,  55 => 18,  48 => 14,  44 => 12,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-form--with-sidebar.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-form--with-sidebar.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["trans" => 18];
        static $filters = ["escape" => 14, "without" => 14];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['trans'],
                ['escape', 'without'],
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
