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

/* modules/contrib/commerce_invoice/templates/commerce-invoice-confirmation.html.twig */
class __TwigTemplate_6d9c05dee7176e0390cb261688722714 extends Template
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
        // line 21
        yield "<table style=\"margin: 15px auto 0 auto; max-width: 768px; font-family: arial,sans-serif\">
  <tbody>
  <tr>
    <td>
      <table style=\"margin-left: auto; margin-right: auto; max-width: 768px; text-align: center;\">
        <tbody>
        <tr>
          <td>
            <a href=\"";
        // line 29
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("<front>"));
        yield "\" style=\"color: #0e69be; text-decoration: none; font-weight: bold; margin-top: 15px;\">";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getStore", [], "any", false, false, true, 29), "label", [], "any", false, false, true, 29), "html", null, true);
        yield "</a>
          </td>
        </tr>
        </tbody>
      </table>
      <table style=\"text-align: center; min-width: 450px; margin: 5px auto 0 auto; border: 1px solid #cccccc; border-radius: 5px; padding: 40px 30px 30px 30px;\">
        <tbody>
        <tr>
          <td style=\"font-size: 30px; padding-bottom: 30px\">";
        // line 37
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Invoice"));
        yield "</td>
        </tr>
        <tr>
          <td style=\"font-weight: bold; padding-top:15px; padding-bottom: 15px; text-align: left; border-top: 1px solid #cccccc;\">
            ";
        // line 41
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Invoice #@number:", ["@number" => CoreExtension::getAttribute($this->env, $this->source, ($context["invoice_entity"] ?? null), "getInvoiceNumber", [], "any", false, false, true, 41)]));
        yield "
          </td>
        </tr>
        <tr>
          <td>
            ";
        // line 46
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("A new invoice has been created for you. It has been attached to this email."));
        yield "
          </td>
        </tr>
        </tbody>
      </table>
    </td>
  </tr>
  </tbody>
</table>
";
        $this->env->getExtension('\Drupal\Core\Template\TwigExtension')
            ->checkDeprecations($context, ["invoice_entity"]);        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "modules/contrib/commerce_invoice/templates/commerce-invoice-confirmation.html.twig";
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
        return array (  82 => 46,  74 => 41,  67 => 37,  54 => 29,  44 => 21,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "modules/contrib/commerce_invoice/templates/commerce-invoice-confirmation.html.twig", "/Applications/MAMP/htdocs/projects/crackers/web/modules/contrib/commerce_invoice/templates/commerce-invoice-confirmation.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = [];
        static $filters = ["escape" => 29, "t" => 37, "trans" => 46];
        static $functions = ["url" => 29];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 't', 'trans'],
                ['url'],
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
