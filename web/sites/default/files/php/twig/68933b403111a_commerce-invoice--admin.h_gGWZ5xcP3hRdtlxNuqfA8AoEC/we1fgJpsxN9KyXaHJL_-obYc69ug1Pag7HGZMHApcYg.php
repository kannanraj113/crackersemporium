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

/* modules/contrib/commerce_invoice/templates/commerce-invoice--admin.html.twig */
class __TwigTemplate_f3141fda8fee72bb00b4022f710aae5f extends Template
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
        // line 20
        yield "
";
        // line 21
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("commerce_order/form"), "html", null, true);
        yield "
";
        // line 22
        $context["invoice_state"] = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getState", [], "any", false, false, true, 22), "getLabel", [], "any", false, false, true, 22);
        // line 23
        yield "
<div class=\"layout-order-form clearfix\">
  <div class=\"layout-region layout-region-order-main\">
    ";
        // line 26
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "invoice_items", [], "any", false, false, true, 26), "html", null, true);
        yield "
    ";
        // line 27
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "total_price", [], "any", false, false, true, 27), "html", null, true);
        yield "

    ";
        // line 29
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "activity", [], "any", false, false, true, 29)) {
            // line 30
            yield "      <h2>";
            yield t("Activity", array());
            yield "</h2>
      ";
            // line 31
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "activity", [], "any", false, false, true, 31), "html", null, true);
            yield "
    ";
        }
        // line 33
        yield "  </div>
  <div class=\"layout-region layout-region-order-secondary\">
    <div class=\"entity-meta\">
      <div class=\"entity-meta__header\">
        <h3 class=\"entity-meta__title\">
          ";
        // line 38
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["invoice_state"] ?? null), "html", null, true);
        yield "
        </h3>
        ";
        // line 40
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(["invoice_date", "due_date"]);
        foreach ($context['_seq'] as $context["_key"] => $context["key"]) {
            // line 41
            yield "          ";
            if ((($_v0 = ($context["invoice"] ?? null)) && is_array($_v0) || $_v0 instanceof ArrayAccess && in_array($_v0::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v0[$context["key"]] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), $context["key"], [], "array", false, false, true, 41))) {
                // line 42
                yield "            <div class=\"form-item\">
              ";
                // line 43
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, (($_v1 = ($context["invoice"] ?? null)) && is_array($_v1) || $_v1 instanceof ArrayAccess && in_array($_v1::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v1[$context["key"]] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), $context["key"], [], "array", false, false, true, 43)), "html", null, true);
                yield "
            </div>
          ";
            }
            // line 46
            yield "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['key'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 47
        yield "      </div>
      <details open class=\"seven-details\">
        <summary role=\"button\" class=\"seven-details__summary\">
          ";
        // line 50
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Customer Information"));
        yield "
        </summary>
        <div class=\"details-wrapper seven-details__wrapper\">
          ";
        // line 53
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(["uid", "mail"]);
        foreach ($context['_seq'] as $context["_key"] => $context["key"]) {
            // line 54
            yield "            ";
            if ((($_v2 = ($context["invoice"] ?? null)) && is_array($_v2) || $_v2 instanceof ArrayAccess && in_array($_v2::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v2[$context["key"]] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), $context["key"], [], "array", false, false, true, 54))) {
                // line 55
                yield "              <div class=\"form-item\">
                ";
                // line 56
                yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, (($_v3 = ($context["invoice"] ?? null)) && is_array($_v3) || $_v3 instanceof ArrayAccess && in_array($_v3::class, CoreExtension::ARRAY_LIKE_CLASSES, true) ? ($_v3[$context["key"]] ?? null) : CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), $context["key"], [], "array", false, false, true, 56)), "html", null, true);
                yield "
              </div>
            ";
            }
            // line 59
            yield "          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['key'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 60
        yield "        </div>
      </details>
      ";
        // line 62
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "billing_information", [], "any", false, false, true, 62)) {
            // line 63
            yield "        <details open class=\"seven-details\">
          <summary role=\"button\" class=\"seven-details__summary\">
            ";
            // line 65
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Billing information"));
            yield "
          </summary>
          <div class=\"details-wrapper seven-details__wrapper\">
            ";
            // line 68
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "billing_information", [], "any", false, false, true, 68), "html", null, true);
            yield "
          </div>
        </details>
      ";
        }
        // line 72
        yield "      ";
        // line 73
        yield "      ";
        if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getState", [], "any", false, false, true, 73), "getTransitions", [], "any", false, false, true, 73))) {
            // line 74
            yield "        <div class=\"entity-meta__header\">
          ";
            // line 75
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "state", [], "any", false, false, true, 75), "html", null, true);
            yield "
        </div>
      ";
        }
        // line 78
        yield "    </div>
  </div>
</div>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["invoice_entity", "invoice"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/commerce_invoice/templates/commerce-invoice--admin.html.twig";
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
        return array (  183 => 78,  177 => 75,  174 => 74,  171 => 73,  169 => 72,  162 => 68,  156 => 65,  152 => 63,  150 => 62,  146 => 60,  140 => 59,  134 => 56,  131 => 55,  128 => 54,  124 => 53,  118 => 50,  113 => 47,  107 => 46,  101 => 43,  98 => 42,  95 => 41,  91 => 40,  86 => 38,  79 => 33,  74 => 31,  69 => 30,  67 => 29,  62 => 27,  58 => 26,  53 => 23,  51 => 22,  47 => 21,  44 => 20,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/commerce_invoice/templates/commerce-invoice--admin.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/modules/contrib/commerce_invoice/templates/commerce-invoice--admin.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 22, "if" => 29, "trans" => 30, "for" => 40];
        static $filters = ["escape" => 21, "t" => 50];
        static $functions = ["attach_library" => 21];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'trans', 'for'],
                ['escape', 't'],
                ['attach_library'],
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
