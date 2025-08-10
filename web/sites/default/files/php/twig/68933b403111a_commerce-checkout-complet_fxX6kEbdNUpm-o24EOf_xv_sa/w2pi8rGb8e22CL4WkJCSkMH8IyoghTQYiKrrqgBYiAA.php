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

/* themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-completion-message.html.twig */
class __TwigTemplate_857deec0758fe00091326f4f0d6357fb extends Template
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
        // line 1
        $macros["svg"] = $this->macros["svg"] = $this->loadTemplate("@belgrade/macros.twig", "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-completion-message.html.twig", 1)->unwrap();
        // line 15
        yield "<div class=\"checkout-complete text-center p-3\">
  ";
        // line 16
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($macros["svg"]->getTemplateForMacro("macro_getIcon", $context, 16, $this->getSourceContext())->macro_getIcon(...["check-circle-fill", 42, 42, "text-success mb-4"]));
        // line 17
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["message"] ?? null), "html", null, true);
        // line 19
        if (($context["payment_instructions"] ?? null)) {
            // line 20
            yield "    <div class=\"checkout-complete__payment-instructions\">
      <h2>";
            // line 21
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Payment instructions"));
            yield "</h2>
      ";
            // line 22
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["payment_instructions"] ?? null), "html", null, true);
            yield "
    </div>
  ";
        }
        // line 25
        yield "</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["message", "payment_instructions"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-completion-message.html.twig";
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
        return array (  68 => 25,  62 => 22,  58 => 21,  55 => 20,  53 => 19,  51 => 17,  49 => 16,  46 => 15,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-completion-message.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/themes/contrib/belgrade/templates/commerce/checkout/commerce-checkout-completion-message.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["import" => 1, "if" => 19];
        static $filters = ["escape" => 17, "t" => 21];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['import', 'if'],
                ['escape', 't'],
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
