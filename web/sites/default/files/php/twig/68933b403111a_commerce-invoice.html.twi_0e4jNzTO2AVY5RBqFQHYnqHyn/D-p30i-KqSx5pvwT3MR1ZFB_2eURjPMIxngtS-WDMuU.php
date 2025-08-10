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

/* modules/contrib/commerce_invoice/templates/commerce-invoice.html.twig */
class __TwigTemplate_294071f301152d06acff54744b355e6e extends Template
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
            'invoice_items' => [$this, 'block_invoice_items'],
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 21
        $context["due_date"] = CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getDueDateTime", [], "any", false, false, true, 21);
        // line 22
        if (($context["logo_url"] ?? null)) {
            // line 23
            yield "  <img src=\"";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["logo_url"] ?? null), "html", null, true);
            yield "\"/>
";
        }
        // line 25
        yield "<div class=\"invoice-header\">
  <table class=\"table-container fullwidth\">
    <tr>
      <td>
        ";
        // line 29
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "billing_information", [], "any", false, false, true, 29)) {
            // line 30
            yield "          <div class=\"billing-information\">
            <h3>";
            // line 31
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Billed to"));
            yield "</h3>
            ";
            // line 32
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice"] ?? null), "billing_information", [], "any", false, false, true, 32), "html", null, true);
            yield "
          </div>
        ";
        }
        // line 35
        yield "      </td>
      <td>
        <table class=\"invoice-table\">
          <tbody>
          <tr>
            <td>";
        // line 40
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Invoice"));
        yield "</td>
            <td>";
        // line 41
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getInvoiceNumber", [], "any", false, false, true, 41), "html", null, true);
        yield "</td>
          </tr>
          <tr>
            <td>";
        // line 44
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Date"));
        yield "</td>
            <td>";
        // line 45
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->env->getFilter('format_date')->getCallable()(CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getInvoiceDateTime", [], "any", false, false, true, 45), "html_date"), "html", null, true);
        yield "</td>
          </tr>
          ";
        // line 47
        if (($context["due_date"] ?? null)) {
            // line 48
            yield "            <tr>
              <td>";
            // line 49
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Due date"));
            yield "</td>
              <td>";
            // line 50
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->env->getFilter('format_date')->getCallable()(($context["due_date"] ?? null), "html_date"), "html", null, true);
            yield "</td>
            </tr>
          ";
        }
        // line 53
        yield "          </tbody>
        </table>
      </td>
    </tr>
  </table>
</div>
<div class=\"invoice-items-container\">
  <table class=\"invoice-items\">
    <thead>
    <tr>
      <th>";
        // line 63
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Description"));
        yield "</th>
      <th>";
        // line 64
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Unit price"));
        yield "</th>
      <th>";
        // line 65
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Quantity"));
        yield "</th>
      <th>";
        // line 66
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Total"));
        yield "</th>
    </tr>
    </thead>
    <tbody>
    ";
        // line 70
        yield from $this->unwrap()->yieldBlock('invoice_items', $context, $blocks);
        // line 84
        yield "    </tbody>
  </table>
</div>
<div class=\"invoice-totals-container\">
  <table class=\"table-container fullwidth\">
    <tr>
      <td>
        <table class=\"invoice-table\">
          <tbody>
          <tr>
            <td>
              ";
        // line 95
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Subtotal"));
        yield "
            </td>
            <td>
              ";
        // line 98
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "subtotal", [], "any", false, false, true, 98)), "html", null, true);
        yield "
            </td>
          </tr>
          ";
        // line 101
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["totals"] ?? null), "adjustments", [], "any", false, false, true, 101));
        foreach ($context['_seq'] as $context["_key"] => $context["adjustment"]) {
            // line 102
            yield "            <tr>
              <td>
                ";
            // line 104
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "label", [], "any", false, false, true, 104), "html", null, true);
            yield "
              </td>
              <td>
                ";
            // line 107
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice(CoreExtension::getAttribute($this->env, $this->source, $context["adjustment"], "total", [], "any", false, false, true, 107)), "html", null, true);
            yield "
              </td>
            </tr>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['adjustment'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 111
        yield "          <tr class=\"invoice-total\">
            <td>
              ";
        // line 113
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Invoice Total"));
        yield "
            </td>
            <td>
              ";
        // line 116
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice(CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getTotalPrice", [], "any", false, false, true, 116)), "html", null, true);
        yield "
            </td>
          </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
</div>
";
        // line 125
        if (($context["payment_terms"] ?? null)) {
            // line 126
            yield "  <div class=\"invoice-payment-terms\">
    ";
            // line 127
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["payment_terms"] ?? null), "html", null, true);
            yield "
  </div>
";
        }
        // line 130
        if (($context["footer_text"] ?? null)) {
            // line 131
            yield "  <div class=\"invoice-footer\">
    ";
            // line 132
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($context["footer_text"] ?? null), "html", null, true);
            yield "
  </div>
";
        }
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["invoice_entity", "logo_url", "invoice", "totals", "payment_terms", "footer_text"]);        yield from [];
    }

    // line 70
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_invoice_items(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 71
        yield "      ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getItems", [], "any", false, false, true, 71));
        foreach ($context['_seq'] as $context["_key"] => $context["invoice_item"]) {
            // line 72
            yield "        <tr>
          <td>";
            // line 73
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["invoice_item"], "label", [], "any", false, false, true, 73), "html", null, true);
            yield " ";
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, $context["invoice_item"], "getDescription", [], "any", false, false, true, 73), "html", null, true);
            yield "</td>
          <td>";
            // line 74
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice(CoreExtension::getAttribute($this->env, $this->source, $context["invoice_item"], "getUnitPrice", [], "any", false, false, true, 74)), "html", null, true);
            yield "</td>
          <td>
            ";
            // line 76
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Twig\Extension\CoreExtension']->formatNumber(CoreExtension::getAttribute($this->env, $this->source, $context["invoice_item"], "getQuantity", [], "any", false, false, true, 76)), "html", null, true);
            yield "
          </td>
          <td>
            ";
            // line 79
            yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\commerce_price\TwigExtension\PriceTwigExtension']->formatPrice(CoreExtension::getAttribute($this->env, $this->source, $context["invoice_item"], "getTotalPrice", [], "any", false, false, true, 79)), "html", null, true);
            yield "
          </td>
        </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['invoice_item'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 83
        yield "    ";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/commerce_invoice/templates/commerce-invoice.html.twig";
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
        return array (  289 => 83,  279 => 79,  273 => 76,  268 => 74,  262 => 73,  259 => 72,  254 => 71,  247 => 70,  237 => 132,  234 => 131,  232 => 130,  226 => 127,  223 => 126,  221 => 125,  209 => 116,  203 => 113,  199 => 111,  189 => 107,  183 => 104,  179 => 102,  175 => 101,  169 => 98,  163 => 95,  150 => 84,  148 => 70,  141 => 66,  137 => 65,  133 => 64,  129 => 63,  117 => 53,  111 => 50,  107 => 49,  104 => 48,  102 => 47,  97 => 45,  93 => 44,  87 => 41,  83 => 40,  76 => 35,  70 => 32,  66 => 31,  63 => 30,  61 => 29,  55 => 25,  49 => 23,  47 => 22,  45 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/commerce_invoice/templates/commerce-invoice.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/modules/contrib/commerce_invoice/templates/commerce-invoice.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 21, "if" => 22, "block" => 70, "for" => 101];
        static $filters = ["escape" => 23, "t" => 31, "format_date" => 45, "commerce_price_format" => 98, "number_format" => 76];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'block', 'for'],
                ['escape', 't', 'format_date', 'commerce_price_format', 'number_format'],
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
